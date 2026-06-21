<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MemberApplicationModel;
use App\Libraries\FileUploader;
use App\Libraries\Mailer;

class AuthController extends BaseController
{
    private UserModel               $users;
    private MemberApplicationModel  $applications;

    public function __construct()
    {
        $this->users        = new UserModel();
        $this->applications = new MemberApplicationModel();
    }

    // ══════════════════════════════════════════════════════════════════════════
    // LOGIN (single form for every role — destination is decided by user.role)
    // ══════════════════════════════════════════════════════════════════════════

    public function login(): string
    {
        return view('auth/login', ['pageTitle' => 'Log In']);
    }

    public function doLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user     = $this->users->findByEmail($email);

        // User not found, or wrong password
        if (! $user || ! $this->users->verifyPassword($password, $user['password_hash'])) {
            return redirect()->back()->withInput()
                ->with('error', 'Invalid email or password.');
        }

        // Gate: account must be active (approved by admin, for members; provisioned, for staff)
        if ($user['status'] !== 'active') {
            $message = match ($user['status']) {
                'pending'  => 'Your application is still under review. You will receive an email when approved.',
                'rejected' => 'Your application was not approved. Please contact hello@mdgoatco.farm.',
                default    => 'Your account is not active. Please contact support.',
            };

            return redirect()->back()->withInput()->with('warning', $message);
        }

        $this->startSession($user);
        $this->users->updateLastLogin($user['id']);

        return redirect()->to($this->dashboardUrlForRole($user['role']))
            ->with('success', 'Welcome back, ' . $user['first_name'] . '!');
    }

    /**
     * Where a freshly logged-in user lands, based on their role.
     * This is the only place "which portal" logic lives now.
     */
    private function dashboardUrlForRole(string $role): string
    {
        return match ($role) {
            'super_admin' => '/admin/dashboard',
            'manager'     => '/manager/dashboard',
            'vet'         => '/vet/dashboard',
            default       => '/member/dashboard', // member
        };
    }

    /**
     * Old staff-portal URLs (auth/admin, auth/manager, auth/vet) now just
     * bounce here so existing bookmarks/links don't 404.
     */
    public function redirectToLogin()
    {
        return redirect()->to('/auth/login');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // MEMBER REGISTRATION (multi-step — Step 1 creates the user + application)
    // ══════════════════════════════════════════════════════════════════════════

    public function register(): string
    {
        return view('auth/register', ['pageTitle' => 'Apply for Goat Banking']);
    }

    public function doRegister()
    {
        $rules = [
            'first_name'      => 'required|min_length[2]|max_length[100]|alpha_space',
            'last_name'       => 'required|min_length[2]|max_length[100]|alpha_space',
            'email'           => 'required|valid_email|is_unique[users.email]',
            'phone'           => 'required|min_length[10]|max_length[20]',
            'dob'             => 'required|valid_date[Y-m-d]',
            'gender'          => 'required|in_list[male,female,other]',
            'address'         => 'required|min_length[5]',
            'nid_number'      => 'required|min_length[6]|max_length[30]',
            'nok_name'        => 'required|min_length[2]|max_length[200]',
            'nok_relationship' => 'required',
            'nok_phone'       => 'required|min_length[10]|max_length[20]',
            'nok_nid_number'  => 'required|min_length[6]|max_length[30]',
            'goats_requested' => 'required',
            'password'        => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1. Create user record (status = pending, role = member)
            $userId = $this->users->insert([
                'email'         => $this->request->getPost('email'),
                'password'      => $this->request->getPost('password'), // hashed by model callback
                'role'          => 'member',
                'status'        => 'pending',
                'first_name'    => $this->request->getPost('first_name'),
                'last_name'     => $this->request->getPost('last_name'),
                'phone'         => $this->request->getPost('phone'),
            ]);

            if (! $userId) {
                throw new \RuntimeException('Could not create user account.');
            }

            // 2. Handle file uploads
            $uploader = new FileUploader();
            $files    = [
                'nid_front'     => $this->request->getFile('nid_front'),
                'nid_back'      => $this->request->getFile('nid_back'),
                'headshot'      => $this->request->getFile('headshot'),
                'nok_nid_front' => $this->request->getFile('nok_nid_front'),
                'nok_nid_back'  => $this->request->getFile('nok_nid_back'),
            ];

            $uploadedPaths = $uploader->uploadApplicationDocs($files, (int) $userId);

            // 3. Create application record
            $this->applications->insert(array_merge([
                'user_id'          => $userId,
                'first_name'       => $this->request->getPost('first_name'),
                'last_name'        => $this->request->getPost('last_name'),
                'dob'              => $this->request->getPost('dob'),
                'gender'           => $this->request->getPost('gender'),
                'phone'            => $this->request->getPost('phone'),
                'address'          => $this->request->getPost('address'),
                'occupation'       => $this->request->getPost('occupation'),
                'nid_number'       => $this->request->getPost('nid_number'),
                'nok_name'         => $this->request->getPost('nok_name'),
                'nok_relationship' => $this->request->getPost('nok_relationship'),
                'nok_phone'        => $this->request->getPost('nok_phone'),
                'nok_address'      => $this->request->getPost('nok_address'),
                'nok_nid_number'   => $this->request->getPost('nok_nid_number'),
                'goats_requested'  => $this->request->getPost('goats_requested'),
                'notes'            => $this->request->getPost('notes'),
                'status'           => 'pending',
            ], $uploadedPaths));

            // 4. Notify all admins of new application
            $notifModel = new \App\Models\NotificationModel();
            $notifModel->notifyAllAdmins(
                'New Goat Banking application',
                $this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name') . ' has submitted an application.',
                'info'
            );

            $db->transCommit();

            // 5. Send confirmation + admin alert emails (failures are logged, never block registration)
            $mailer = new Mailer();
            $mailer->send(
                $this->request->getPost('email'),
                'Application received — MD Goatco Farm',
                'application_received',
                ['firstName' => $this->request->getPost('first_name')]
            );
            $mailer->sendToAdmins(
                'New Goat Banking application — ' . $this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name'),
                'new_application_admin_alert',
                [
                    'applicantName'  => $this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name'),
                    'goatsRequested' => $this->request->getPost('goats_requested'),
                ]
            );

            return redirect()->to('/auth/status')
                ->with('success', 'Application submitted! We will review it within 2–3 working days and email you at ' . $this->request->getPost('email') . '.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Registration failed: ' . $e->getMessage());

            return redirect()->back()->withInput()
                ->with('error', 'Registration failed. Please try again or contact support.');
        }
    }

    // ══════════════════════════════════════════════════════════════════════════
    // APPLICATION STATUS CHECK
    // ══════════════════════════════════════════════════════════════════════════

    public function checkStatus(): string
    {
        return view('auth/status', ['pageTitle' => 'Check Application Status']);
    }

    public function doCheckStatus(): string
    {
        $email  = $this->request->getPost('email');
        $result = null;
        $status = null;

        if ($email) {
            $application = $this->applications->findByEmail($email);

            if ($application) {
                $status = $application['status'];
                $result = $application;
            } else {
                $status = 'not_found';
            }
        }

        return view('auth/status', [
            'pageTitle'   => 'Check Application Status',
            'email'       => $email,
            'status'      => $status,
            'application' => $result,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // PASSWORD RESET
    // ══════════════════════════════════════════════════════════════════════════

    public function forgotPassword(): string
    {
        return view('auth/forgot_password', ['pageTitle' => 'Reset Password']);
    }

    public function doForgotPassword()
    {
        $email = $this->request->getPost('email');
        $user  = $this->users->findByEmail($email);

        // Always show success message (don't reveal whether email exists)
        if ($user && $user['status'] === 'active') {
            $token = bin2hex(random_bytes(32));
            $this->users->setResetToken($user['id'], $token);

            $mailer = new Mailer();
            $mailer->send(
                $user['email'],
                'Reset your password — MD Goatco Farm',
                'password_reset',
                ['firstName' => $user['first_name'], 'token' => $token],
                $user['first_name'] . ' ' . $user['last_name']
            );

            log_message('info', 'Password reset requested for ' . $email);
        }

        return redirect()->to('/auth/forgot-password')
            ->with('success', 'If an account with that email exists, a reset link has been sent.');
    }

    public function resetPassword(string $token): string
    {
        $user = $this->users->findByResetToken($token);

        if (! $user) {
            return redirect()->to('/auth/forgot-password')
                ->with('error', 'This reset link is invalid or has expired.');
        }

        return view('auth/reset_password', ['pageTitle' => 'Reset Password', 'token' => $token]);
    }

    public function doResetPassword()
    {
        $rules = [
            'token'            => 'required',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $token = $this->request->getPost('token');
        $user  = $this->users->findByResetToken($token);

        if (! $user) {
            return redirect()->to('/auth/forgot-password')
                ->with('error', 'This reset link is invalid or has expired.');
        }

        $this->users->update($user['id'], [
            'password' => $this->request->getPost('password'), // model hashes it
        ]);
        $this->users->clearResetToken($user['id']);

        return redirect()->to('/auth/login')
            ->with('success', 'Password updated. Please log in.');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // LOGOUT
    // ══════════════════════════════════════════════════════════════════════════

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'You have been signed out.');
    }
}
