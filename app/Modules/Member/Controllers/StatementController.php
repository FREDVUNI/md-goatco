<?php
declare(strict_types=1);
namespace App\Modules\Member\Controllers;
use App\Controllers\BaseController;
use App\Models\TransactionModel;
class StatementController extends BaseController
{
    public function index(): string
    {
        $txns = new TransactionModel();
        $uid  = $this->currentUserId();
        return $this->dashboardView('member/statements', [
            'pageTitle'    => 'Statements',
            'transactions' => $txns->getByMember($uid),
            'balance'      => $txns->getCurrentBalance($uid),
            'totalCredited'=> $txns->getTotalCredited($uid),
        ]);
    }
    public function download() { return redirect()->to('/member/statements')->with('info','PDF export coming soon.'); }
}
