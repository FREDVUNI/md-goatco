<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;

class AccountController extends BaseController
{
    public function index(): string
    {
        $user = (new UserModel())->find($this->currentUserId());
        return $this->dashboardView('account/index', ['pageTitle' => 'My Account', 'user' => $user]);
    }

    public function update()
    {
        $users = new UserModel();
        $users->update($this->currentUserId(), [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'phone'      => $this->request->getPost('phone'),
        ]);

        $this->startSession($users->find($this->currentUserId()));

        return redirect()->to('/account')->with('success', 'Account updated.');
    }

    public function changePassword()
    {
        $users = new UserModel();
        $user  = $users->find($this->currentUserId());

        if (! $users->verifyPassword($this->request->getPost('current_password'), $user['password_hash'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }
        if ($this->request->getPost('password') !== $this->request->getPost('password_confirm')) {
            return redirect()->back()->with('error', 'New passwords do not match.');
        }

        $users->update($this->currentUserId(), ['password' => $this->request->getPost('password')]);

        return redirect()->to('/account')->with('success', 'Password updated.');
    }
}
