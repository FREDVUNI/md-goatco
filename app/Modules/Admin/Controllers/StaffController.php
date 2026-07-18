<?php
declare(strict_types=1);
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Libraries\EmailService;

class StaffController extends BaseController
{
    private UserModel $users;
    public function __construct() { $this->users = new UserModel(); }

    public function index(): string
    {
        $search = $this->searchTerm();
        [$staff, $pager] = $this->paginateBuilder($this->users->getStaffQuery($search));

        return $this->dashboardView('admin/staff', [
            'pageTitle'   => 'Staff Accounts',
            'staff'       => $staff,
            'pager'       => $pager,
            'search'      => $search,
            'pendingCount'=> (new \App\Models\MemberApplicationModel())->countPending(),
        ]);
    }

    public function export()
    {
        $rows = $this->users->getStaffQuery($this->searchTerm())->get()->getResultArray();
        return $this->downloadCsv($rows, 'staff_' . date('Y-m-d') . '.csv');
    }

    public function create(): string
    {
        return $this->dashboardView('admin/staff_create', ['pageTitle'=>'Create Staff Account']);
    }

    public function store()
    {
        if (! $this->validate(['first_name'=>'required','last_name'=>'required','email'=>'required|valid_email|is_unique[users.email]','role'=>'required|in_list[manager,vet,super_admin]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $tempPw = ucfirst(strtolower($this->request->getPost('first_name'))).'@'.date('Y').'!';
        $userId = $this->users->insert([
            'email'=>$this->request->getPost('email'),'password'=>$tempPw,
            'role'=>$this->request->getPost('role'),'status'=>'active',
            'first_name'=>$this->request->getPost('first_name'),'last_name'=>$this->request->getPost('last_name'),
            'phone'=>$this->request->getPost('phone'),
        ]);
        try {
            $user = $this->users->find($userId);
            (new EmailService())->sendStaffWelcome($user, $tempPw);
        } catch (\Throwable $e) {}
        return redirect()->to('/admin/staff')->with('success','Staff account created. Welcome email sent to '.$this->request->getPost('email').'.');
    }

    public function edit(int $id)
    {
        $user = $this->users->find($id);
        if (! $user) return redirect()->to('/admin/staff')->with('error','User not found.');
        return $this->dashboardView('admin/staff_create', ['pageTitle'=>'Edit Staff — '.$user['first_name'],'staff'=>$user]);
    }

    public function update(int $id)
    {
        $this->users->update($id, [
            'first_name'=>$this->request->getPost('first_name'),'last_name'=>$this->request->getPost('last_name'),
            'phone'=>$this->request->getPost('phone'),'role'=>$this->request->getPost('role'),
        ]);
        return redirect()->to('/admin/staff')->with('success','Staff account updated.');
    }

    public function deactivate(int $id)
    {
        $this->users->deactivate($id);
        return redirect()->to('/admin/staff')->with('success','Staff account deactivated.');
    }

    public function resetPassword(int $id)
    {
        $user  = $this->users->find($id);
        $newPw = ucfirst(strtolower($user['first_name'])).'@'.date('Y').'!';
        $this->users->update($id, ['password'=>$newPw]);
        try { (new EmailService())->sendStaffWelcome($user, $newPw); } catch (\Throwable $e) {}
        return redirect()->to('/admin/staff')->with('success','Password reset. New credentials emailed to '.$user['email'].'.');
    }
}
