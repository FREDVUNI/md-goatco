<?php
declare(strict_types=1);
namespace App\Modules\Member\Controllers;
use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Libraries\PesaPal;
class WalletController extends BaseController
{
    public function topup(): string
    {
        $txns = new TransactionModel();
        return $this->dashboardView('member/wallet', ['pageTitle'=>'Top Up Wallet','balance'=>$txns->getCurrentBalance($this->currentUserId())]);
    }
    public function initiateTopup()
    {
        return redirect()->to('/payments/initiate');
    }
    public function topupStatus(string $reference): string
    {
        return $this->dashboardView('member/statements', ['pageTitle'=>'Payment Status']);
    }
}
