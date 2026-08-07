<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\UserModel;
use App\Models\MemberApplicationModel;
use App\Libraries\FileUploader;
use App\Libraries\EmailService;

class AuthController extends BaseController
{
    private UserModel $users;
    private MemberApplicationModel $applications;

    public function __construct()
    {
        $this->users        = new UserModel();
        $this->applications = new MemberApplicationModel();
    }

    public function login(): string
    {
        return view('auth/login', [
            'pageTitle'     => 'Log In',
            'googleEnabled' => ! empty(env('GOOGLE_CLIENT_ID')),
        ]);
    }

    public function doLogin()
    {
        if ($this->tooManyAttempts('login', 10, 300)) {
            return redirect()->back()->withInput()->with('error', 'Too many login attempts. Please wait a few minutes and try again.');
        }
        if (! $this->validate(['email' => 'required|valid_email', 'password' => 'required'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $user = $this->users->findByEmail($this->request->getPost('email'));
        if (! $user || ! $this->users->verifyPassword($this->request->getPost('password'), $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }
        if ($user['status'] !== 'active') {
            return redirect()->back()->withInput()->with('warning', match($user['status']) {
                'pending'  => 'Your application is still under review. You will receive an email when approved.',
                'rejected' => 'Your application was not approved. Please contact hello@mdgoatco.farm.',
                default    => 'Your account is not active. Please contact support.',
            });
        }
        $this->startSession($user);
        $this->users->updateLastLogin((int) $user['id']);
        return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . $user['first_name'] . '!');
    }

    // ── GOOGLE SIGN-IN ───────────────────────────────────────────────────
    // Google only ever signs in an EXISTING active account — it deliberately
    // can't create one, since membership requires the KYC steps in the
    // registration wizard (ID documents, next of kin, admin approval).
    // Requires GOOGLE_CLIENT_ID / GOOGLE_CLIENT_SECRET in .env; the button
    // is hidden on the login page until those are set.

    public function googleRedirect()
    {
        $clientId = env('GOOGLE_CLIENT_ID');
        if (empty($clientId)) {
            return redirect()->to('/auth/login')->with('error', 'Google sign-in is not configured yet.');
        }

        $state = bin2hex(random_bytes(16));
        session()->set('google_oauth_state', $state);

        $params = http_build_query([
            'client_id'     => $clientId,
            'redirect_uri'  => $this->googleRedirectUri(),
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'state'         => $state,
            'prompt'        => 'select_account',
        ]);
        return redirect()->to('https://accounts.google.com/o/oauth2/v2/auth?' . $params);
    }

    public function googleCallback()
    {
        $clientId     = env('GOOGLE_CLIENT_ID');
        $clientSecret = env('GOOGLE_CLIENT_SECRET');
        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->to('/auth/login')->with('error', 'Google sign-in is not configured yet.');
        }

        $state = $this->request->getGet('state');
        $expectedState = session()->get('google_oauth_state');
        session()->remove('google_oauth_state');
        if (! $state || ! $expectedState || ! hash_equals($expectedState, $state)) {
            return redirect()->to('/auth/login')->with('error', 'Google sign-in session expired — please try again.');
        }

        $code = $this->request->getGet('code');
        if (! $code) {
            return redirect()->to('/auth/login')->with('error', 'Google sign-in was cancelled.');
        }

        $token = $this->googleHttpPost('https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri'  => $this->googleRedirectUri(),
            'grant_type'    => 'authorization_code',
        ]);
        if (empty($token['access_token'])) {
            log_message('error', 'Google OAuth token exchange failed: ' . json_encode($token));
            return redirect()->to('/auth/login')->with('error', 'Could not sign in with Google. Please try again.');
        }

        $profile = $this->googleHttpGet('https://www.googleapis.com/oauth2/v3/userinfo', $token['access_token']);
        $email = strtolower(trim($profile['email'] ?? ''));
        if ($email === '' || empty($profile['email_verified'])) {
            return redirect()->to('/auth/login')->with('error', 'Your Google account has no verified email address.');
        }

        $user = $this->users->findByEmail($email);
        if (! $user) {
            session()->setFlashdata('prefill_email', $email);
            return redirect()->to('/auth/register')->with('warning', "No Goat Banking account found for {$email} yet. Apply below to get started.");
        }
        if ($user['status'] !== 'active') {
            return redirect()->to('/auth/login')->with('warning', match ($user['status']) {
                'pending'  => 'Your application is still under review. You will receive an email when approved.',
                'rejected' => 'Your application was not approved. Please contact hello@mdgoatco.farm.',
                default    => 'Your account is not active. Please contact support.',
            });
        }

        $this->startSession($user);
        $this->users->updateLastLogin((int) $user['id']);
        return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . $user['first_name'] . '!');
    }

    private function googleRedirectUri(): string
    {
        return rtrim(base_url(), '/') . '/auth/google/callback';
    }

    private function googleHttpPost(string $url, array $fields): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($fields),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
        ]);
        $raw = curl_exec($ch);
        curl_close($ch);
        return json_decode((string) $raw, true) ?? [];
    }

    private function googleHttpGet(string $url, string $accessToken): array
    {
        $ch = curl_init($url . '?access_token=' . urlencode($accessToken));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
        ]);
        $raw = curl_exec($ch);
        curl_close($ch);
        return json_decode((string) $raw, true) ?? [];
    }

    public function redirectToLogin() { return redirect()->to('/auth/login'); }

    public function register(): string
    {
        return view('auth/register', [
            'pageTitle'    => 'Apply for Goat Banking',
            'errors'       => session('errors'),
            'prefillEmail' => session()->getFlashdata('prefill_email'),
        ]);
    }

    public function doRegister()
    {
        // Client-side checks (validate.js, the `accept` attribute) are UX only —
        // an attacker can bypass them entirely, so KYC documents get validated
        // server-side too: real content-sniffed MIME type (not the client's
        // claimed Content-Type), extension, and size, before anything is saved.
        $fileRule = 'max_size[FIELD,5120]|ext_in[FIELD,jpg,jpeg,png,pdf]|mime_in[FIELD,image/jpeg,image/png,application/pdf]';
        $rules = [
            'first_name'=>'required|min_length[2]','last_name'=>'required|min_length[2]',
            'email'=>'required|valid_email|is_unique[users.email]','phone'=>'required|min_length[10]',
            'dob'=>'required|valid_date[Y-m-d]','gender'=>'required|in_list[male,female,other]',
            'address'=>'required|min_length[5]','nid_number'=>'required|min_length[6]',
            'nok_name'=>'required|min_length[2]','nok_relationship'=>'required',
            'nok_phone'=>'required|min_length[10]','nok_nid_number'=>'required|min_length[6]',
            'goats_requested'=>'required','password'=>'required|min_length[8]',
            'password_confirm'=>'required|matches[password]',
            'nid_front'     => 'uploaded[nid_front]|' . str_replace('FIELD', 'nid_front', $fileRule),
            'nid_back'      => 'uploaded[nid_back]|' . str_replace('FIELD', 'nid_back', $fileRule),
            'nok_nid_front' => 'uploaded[nok_nid_front]|' . str_replace('FIELD', 'nok_nid_front', $fileRule),
            'nok_nid_back'  => 'uploaded[nok_nid_back]|' . str_replace('FIELD', 'nok_nid_back', $fileRule),
            'headshot'      => 'permit_empty|' . str_replace('FIELD', 'headshot', $fileRule),
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $db = \Config\Database::connect();
        $db->transBegin();
        try {
            $userId = $this->users->insert([
                'email'=>$this->request->getPost('email'),'password'=>$this->request->getPost('password'),
                'role'=>'member','status'=>'pending',
                'first_name'=>$this->request->getPost('first_name'),'last_name'=>$this->request->getPost('last_name'),
                'phone'=>$this->request->getPost('phone'),
            ]);
            if (! $userId) throw new \RuntimeException('Could not create account.');
            $uploader = new FileUploader();
            $paths = $uploader->uploadApplicationDocs([
                'nid_front'=>$this->request->getFile('nid_front'),'nid_back'=>$this->request->getFile('nid_back'),
                'headshot'=>$this->request->getFile('headshot'),
                'nok_nid_front'=>$this->request->getFile('nok_nid_front'),'nok_nid_back'=>$this->request->getFile('nok_nid_back'),
            ], (int)$userId);
            $this->applications->insert(array_merge([
                'user_id'=>$userId,'first_name'=>$this->request->getPost('first_name'),
                'last_name'=>$this->request->getPost('last_name'),'dob'=>$this->request->getPost('dob'),
                'gender'=>$this->request->getPost('gender'),'phone'=>$this->request->getPost('phone'),
                'address'=>$this->request->getPost('address'),'occupation'=>$this->request->getPost('occupation'),
                'nid_number'=>$this->request->getPost('nid_number'),'nok_name'=>$this->request->getPost('nok_name'),
                'nok_relationship'=>$this->request->getPost('nok_relationship'),'nok_phone'=>$this->request->getPost('nok_phone'),
                'nok_address'=>$this->request->getPost('nok_address'),'nok_nid_number'=>$this->request->getPost('nok_nid_number'),
                'goats_requested'=>$this->request->getPost('goats_requested'),'notes'=>$this->request->getPost('notes'),
                'status'=>'pending',
            ], $paths));
            $db->transCommit();
            try {
                $mailer = new EmailService();
                $user   = $this->users->find($userId);
                $app    = $this->applications->findByUserId((int)$userId);
                $mailer->sendApplicationReceived($user, $app);
                foreach ($this->users->getByRole('super_admin') as $admin) {
                    $mailer->sendNewApplicationAlert($admin, array_merge($app, ['email'=>$user['email']]));
                }
            } catch (\Throwable $e) { log_message('error', 'Email failed: '.$e->getMessage()); }
            (new \App\Models\NotificationModel())->notifyRole(
                'super_admin',
                'New membership application',
                $user['first_name'].' '.$user['last_name'].' applied for Goat Banking.',
                'info',
                'admin/applications/'.$app['id']
            );
            return redirect()->to('/auth/status?email=' . urlencode($this->request->getPost('email')))
                ->with('success', 'Application submitted! We\'ve sent a confirmation email to your inbox. Your application is now pending review — we\'ll get you approved as soon as possible.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Registration failed: '.$e->getMessage());
        }
    }

    public function checkStatus(): string
    {
        // Landing here right after registration (?email=...) — run the same
        // lookup immediately instead of making the applicant re-type their
        // email into the form they just came from.
        $email = $this->request->getGet('email');
        if (! $email) {
            return view('auth/status', ['pageTitle' => 'Check Application Status']);
        }
        $app = $this->applications->findByEmail($email);
        return view('auth/status', [
            'pageTitle'   => 'Check Application Status',
            'email'       => $email,
            'status'      => $app ? $app['status'] : 'not_found',
            'application' => $app,
        ]);
    }

    public function doCheckStatus(): string
    {
        $email = $this->request->getPost('email');
        $app   = $email ? $this->applications->findByEmail($email) : null;
        return view('auth/status', ['pageTitle'=>'Check Application Status','email'=>$email,'status'=>$app?$app['status']:'not_found','application'=>$app]);
    }

    public function forgotPassword(): string { return view('auth/forgot_password', ['pageTitle'=>'Reset Password']); }

    public function doForgotPassword()
    {
        if ($this->tooManyAttempts('forgot_password', 5, 900)) {
            return redirect()->to('/auth/forgot-password')->with('error', 'Too many requests. Please wait a few minutes and try again.');
        }
        $email = $this->request->getPost('email');
        $user  = $this->users->findByEmail($email);
        if ($user && $user['status']==='active') {
            $token = bin2hex(random_bytes(32));
            $this->users->setResetToken((int) $user['id'], $token);
            try { (new EmailService())->sendPasswordReset($user, $token); } catch (\Throwable $e) {}
        }
        return redirect()->to('/auth/forgot-password')->with('success', 'If an account exists for that email, a reset link has been sent.');
    }

    public function resetPassword(string $token)
    {
        if (! $this->users->findByResetToken($token)) {
            return redirect()->to('/auth/forgot-password')->with('error', 'This link is invalid or has expired.');
        }
        return view('auth/reset_password', ['pageTitle'=>'Set New Password','token'=>$token]);
    }

    public function doResetPassword()
    {
        if (! $this->validate(['token'=>'required','password'=>'required|min_length[8]','password_confirm'=>'required|matches[password]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $token = $this->request->getPost('token');
        $user  = $this->users->findByResetToken($token);
        if (! $user) return redirect()->to('/auth/forgot-password')->with('error', 'This link is invalid or has expired.');
        $this->users->update($user['id'], ['password'=>$this->request->getPost('password')]);
        $this->users->clearResetToken((int) $user['id']);
        return redirect()->to('/auth/login')->with('success', 'Password updated. Please log in.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'You have been signed out.');
    }
}
