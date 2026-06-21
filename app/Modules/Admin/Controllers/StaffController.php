<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\NotificationModel;
use App\Libraries\Mailer;

class StaffController extends BaseController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function index(): string
    {
        return $this->dashboardView('admin/staff', [
            'pageTitle' => 'Staff Accounts',
            'staff'     => $this->users->getStaff(),
        ]);
    }

    public function create(): string
    {
        return $this->dashboardView('admin/staff_create', [
            'pageTitle' => 'Create Staff Account',
        ]);
    }

    public function store()
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name'  => 'required|min_length[2]|max_length[100]',
            'email'      => 'required|valid_email|is_unique[users.email]',
            'phone'      => 'permit_empty|min_length[10]|max_length[20]',
            'role'       => 'required|in_list[manager,vet]',
            'password'   => 'required|min_length[8]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = $this->users->insert([
            'first_name'  => $this->request->getPost('first_name'),
            'last_name'   => $this->request->getPost('last_name'),
            'email'       => $this->request->getPost('email'),
            'phone'       => $this->request->getPost('phone'),
            'role'        => $this->request->getPost('role'),
            'password'    => $this->request->getPost('password'), // hashed by model
            'status'      => 'active',
            'created_by'  => $this->currentUserId(),
        ]);

        if (! $userId) {
            return redirect()->back()->withInput()
                             ->with('error', 'Could not create staff account. Please try again.');
        }

        // Welcome notification
        $notifModel = new NotificationModel();
        $notifModel->notifyUser(
            (int) $userId,
            'Welcome to MD Goatco Farm Ltd',
            'Your ' . ucfirst($this->request->getPost('role')) . ' account has been created. Please log in and change your password.',
            'info'
        );

        $mailer = new Mailer();
        $mailer->send(
            $this->request->getPost('email'),
            'Welcome to MD Goatco Farm — your account is ready',
            'staff_account_created',
            [
                'firstName'    => $this->request->getPost('first_name'),
                'email'        => $this->request->getPost('email'),
                'tempPassword' => $this->request->getPost('password'),
                'role'         => $this->request->getPost('role'),
                'loginPath'    => $this->request->getPost('role') === 'vet' ? 'vet' : 'manager',
            ],
            $this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name')
        );

        return redirect()->to('/admin/staff')
                         ->with('success', 'Staff account created for ' . $this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name') . '.');
    }

    public function edit(int $id): string
    {
        $staff = $this->users->find($id);

        if (! $staff || ! in_array($staff['role'], ['manager', 'vet'], true)) {
            return redirect()->to('/admin/staff')->with('error', 'Staff member not found.');
        }

        return $this->dashboardView('admin/staff_edit', [
            'pageTitle' => 'Edit Staff — ' . $staff['first_name'] . ' ' . $staff['last_name'],
            'staff'     => $staff,
        ]);
    }

    public function update(int $id)
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name'  => 'required|min_length[2]|max_length[100]',
            'email'      => "required|valid_email|is_unique[users.email,id,{$id}]",
            'phone'      => 'permit_empty|min_length[10]|max_length[20]',
            'role'       => 'required|in_list[manager,vet]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'email'      => $this->request->getPost('email'),
            'phone'      => $this->request->getPost('phone'),
            'role'       => $this->request->getPost('role'),
        ];

        // Optional password reset
        if ($this->request->getPost('new_password')) {
            $data['password'] = $this->request->getPost('new_password');
        }

        $this->users->update($id, $data);

        return redirect()->to('/admin/staff')->with('success', 'Staff account updated.');
    }

    public function deactivate(int $id)
    {
        $staff = $this->users->find($id);

        if (! $staff || $staff['role'] === 'super_admin') {
            return redirect()->to('/admin/staff')->with('error', 'Cannot deactivate this account.');
        }

        $this->users->deactivate($id);

        return redirect()->to('/admin/staff')
                         ->with('success', $staff['first_name'] . '\'s account has been deactivated.');
    }

    public function resetPassword(int $id)
    {
        $staff = $this->users->find($id);

        if (! $staff) {
            return redirect()->to('/admin/staff')->with('error', 'Staff member not found.');
        }

        $newPassword = bin2hex(random_bytes(6)); // temporary 12-char password
        $this->users->update($id, ['password' => $newPassword]);

        $mailer = new Mailer();
        $mailer->send(
            $staff['email'],
            'Your MD Goatco Farm password has been reset',
            'staff_password_reset',
            [
                'firstName'    => $staff['first_name'],
                'tempPassword' => $newPassword,
                'loginPath'    => $staff['role'] === 'vet' ? 'vet' : 'manager',
            ],
            $staff['first_name'] . ' ' . $staff['last_name']
        );

        return redirect()->to('/admin/staff')
                         ->with('success', 'Password reset. A temporary password has been emailed to ' . $staff['email'] . '.');
    }
}
