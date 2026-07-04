<?php
declare(strict_types=1);
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;
use App\Models\MemberApplicationModel;
use App\Models\UserModel;
use App\Libraries\EmailService;

class ApplicationController extends BaseController
{
    private MemberApplicationModel $applications;
    private UserModel $users;

    public function __construct()
    {
        $this->applications = new MemberApplicationModel();
        $this->users        = new UserModel();
    }

    public function index(): string
    {
        return $this->dashboardView('admin/applications', [
            'pageTitle'   => 'Applications',
            'pending'     => $this->applications->where('status','pending')->orderBy('created_at','DESC')->findAll(),
            'approved'    => $this->applications->where('status','approved')->orderBy('reviewed_at','DESC')->limit(20)->findAll(),
            'rejected'    => $this->applications->where('status','rejected')->orderBy('reviewed_at','DESC')->limit(20)->findAll(),
            'pendingCount'=> $this->applications->countPending(),
        ]);
    }

    public function show(int $id): string
    {
        $app = $this->applications->find($id);
        if (! $app) return redirect()->to('/admin/applications')->with('error','Application not found.');
        return $this->dashboardView('admin/application_detail', [
            'pageTitle'   => $app['first_name'].' '.$app['last_name'].' — Application',
            'application' => $app,
            'pendingCount'=> $this->applications->countPending(),
        ]);
    }

    public function approve(int $id)
    {
        $app = $this->applications->find($id);
        if (! $app) return redirect()->to('/admin/applications')->with('error','Not found.');
        $this->applications->update($id, ['status'=>'approved','reviewed_by'=>$this->currentUserId(),'reviewed_at'=>date('Y-m-d H:i:s')]);
        $this->users->update($app['user_id'], ['status'=>'active']);
        try {
            $user = $this->users->find($app['user_id']);
            (new EmailService())->sendApproval($user);
        } catch (\Throwable $e) { log_message('error','Approval email failed: '.$e->getMessage()); }
        return redirect()->to('/admin/applications')->with('success',$app['first_name'].' '.$app['last_name'].'\'s application has been approved.');
    }

    public function reject(int $id)
    {
        $app    = $this->applications->find($id);
        $reason = $this->request->getPost('rejection_reason') ?? '';
        if (! $app) return redirect()->to('/admin/applications')->with('error','Not found.');
        $this->applications->update($id, ['status'=>'rejected','rejection_reason'=>$reason,'reviewed_by'=>$this->currentUserId(),'reviewed_at'=>date('Y-m-d H:i:s')]);
        $this->users->update($app['user_id'], ['status'=>'rejected']);
        try {
            $user = $this->users->find($app['user_id']);
            (new EmailService())->sendRejection($user, $reason);
        } catch (\Throwable $e) {}
        return redirect()->to('/admin/applications')->with('success','Application rejected.');
    }

    public function requestInfo(int $id)
    {
        $app  = $this->applications->find($id);
        $note = $this->request->getPost('info_request_note') ?? '';
        if (! $app) return redirect()->to('/admin/applications')->with('error','Not found.');
        $this->applications->update($id, ['status'=>'info_requested','info_request_note'=>$note]);
        try {
            $user = $this->users->find($app['user_id']);
            (new EmailService())->sendInfoRequest($user, $note);
        } catch (\Throwable $e) {}
        return redirect()->to('/admin/applications/'.$id)->with('success','Information request sent.');
    }
}
