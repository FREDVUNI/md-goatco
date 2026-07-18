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
        return view('auth/login', ['pageTitle' => 'Log In']);
    }

    public function doLogin()
    {
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

    public function redirectToLogin() { return redirect()->to('/auth/login'); }

    public function register(): string
    {
        return view('auth/register', [
            'pageTitle' => 'Apply for Goat Banking',
            'errors'    => session('errors'),
        ]);
    }

    public function doRegister()
    {
        $rules = [
            'first_name'=>'required|min_length[2]','last_name'=>'required|min_length[2]',
            'email'=>'required|valid_email|is_unique[users.email]','phone'=>'required|min_length[10]',
            'dob'=>'required|valid_date[Y-m-d]','gender'=>'required|in_list[male,female,other]',
            'address'=>'required|min_length[5]','nid_number'=>'required|min_length[6]',
            'nok_name'=>'required|min_length[2]','nok_relationship'=>'required',
            'nok_phone'=>'required|min_length[10]','nok_nid_number'=>'required|min_length[6]',
            'goats_requested'=>'required','password'=>'required|min_length[8]',
            'password_confirm'=>'required|matches[password]',
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
            return redirect()->to('/auth/status')->with('success', 'Application submitted! We\'ve sent a confirmation email to your inbox. Your application is now pending review — we\'ll get you approved as soon as possible.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Registration failed: '.$e->getMessage());
        }
    }

    public function checkStatus(): string { return view('auth/status', ['pageTitle'=>'Check Application Status']); }

    public function doCheckStatus(): string
    {
        $email = $this->request->getPost('email');
        $app   = $email ? $this->applications->findByEmail($email) : null;
        return view('auth/status', ['pageTitle'=>'Check Application Status','email'=>$email,'status'=>$app?$app['status']:'not_found','application'=>$app]);
    }

    public function forgotPassword(): string { return view('auth/forgot_password', ['pageTitle'=>'Reset Password']); }

    public function doForgotPassword()
    {
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
