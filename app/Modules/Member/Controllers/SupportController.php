<?php
declare(strict_types=1);
namespace App\Modules\Member\Controllers;
use App\Controllers\BaseController;
class SupportController extends BaseController
{
    public function index(): string { return $this->dashboardView('member/support', ['pageTitle'=>'Support']); }
    public function send()
    {
        log_message('info','Support request from member '.$this->currentUserId().': '.$this->request->getPost('message'));
        return redirect()->to('/member/support')->with('success','Your message has been sent. We will get back to you within 1–2 working days.');
    }
}
