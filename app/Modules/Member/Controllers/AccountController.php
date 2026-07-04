<?php
declare(strict_types=1);
namespace App\Modules\Member\Controllers;
use App\Controllers\BaseController;
use App\Models\UserModel;
class AccountController extends BaseController
{
    public function index(): string
    {
        return $this->dashboardView('member/account', ['pageTitle'=>'My Account','user'=>$this->currentUser()]);
    }
    public function update()
    {
        (new UserModel())->update($this->currentUserId(), ['phone'=>$this->request->getPost('phone')]);
        return redirect()->to('/member/account')->with('success','Account updated.');
    }
    public function changePassword()
    {
        $users = new UserModel();
        $user  = $users->find($this->currentUserId());
        if (! $users->verifyPassword($this->request->getPost('current_password'), $user['password_hash'])) {
            return redirect()->back()->with('error','Current password is incorrect.');
        }
        if ($this->request->getPost('password') !== $this->request->getPost('password_confirm')) {
            return redirect()->back()->with('error','New passwords do not match.');
        }
        $users->update($this->currentUserId(), ['password'=>$this->request->getPost('password')]);
        return redirect()->to('/member/account')->with('success','Password updated.');
    }
}
