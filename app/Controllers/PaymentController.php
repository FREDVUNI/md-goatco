<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Libraries\PesaPal;
use App\Libraries\EmailService;
use App\Models\TransactionModel;
use App\Models\UserModel;

class PaymentController extends BaseController
{
    private PesaPal $pesapal;
    private TransactionModel $transactions;
    private UserModel $users;

    public function __construct()
    {
        $this->pesapal      = new PesaPal();
        $this->transactions = new TransactionModel();
        $this->users        = new UserModel();
    }

    public function initiate()
    {
        $user   = $this->users->find($this->currentUserId());
        $amount = (int) $this->request->getPost('amount');
        $desc   = $this->request->getPost('description') ?? 'Goat Banking payment';
        if ($amount < 1000) return redirect()->back()->with('error', 'Minimum payment is UGX 1,000.');
        try {
            $reference = $this->pesapal->generateReference($user['id']);
            $order = $this->pesapal->submitOrder([
                'id'=>$reference,'amount'=>$amount,'description'=>$desc,
                'billing'=>['email_address'=>$user['email'],'phone_number'=>$user['phone']??'','first_name'=>$user['first_name'],'last_name'=>$user['last_name']],
            ]);
            $db = \Config\Database::connect();
            $db->table('pending_payments')->insert([
                'user_id'=>$user['id'],'reference'=>$reference,
                'order_tracking_id'=>$order['order_tracking_id'],
                'amount'=>$amount,'description'=>$desc,'status'=>'pending','created_at'=>date('Y-m-d H:i:s'),
            ]);
            return redirect()->to($order['redirect_url']);
        } catch (\Throwable $e) {
            log_message('error', 'Payment initiation failed: '.$e->getMessage());
            return redirect()->back()->with('error', 'Could not initiate payment. Please try again.');
        }
    }

    public function callback()
    {
        $orderTrackingId = $this->request->getGet('OrderTrackingId');
        $merchantRef     = $this->request->getGet('OrderMerchantReference');
        if (! $orderTrackingId) return redirect()->to('/member/statements')->with('error', 'Invalid payment callback.');
        try {
            $status = $this->pesapal->getTransactionStatus($orderTrackingId);
            $this->processPaymentStatus($orderTrackingId, $merchantRef, $status);
            if (($status['payment_status_description']??'') === 'Completed') {
                return redirect()->to('/dashboard')->with('success', 'Payment confirmed! Your account has been updated.');
            }
            return redirect()->to('/dashboard')->with('warning', 'Payment '.strtolower($status['payment_status_description']??'pending').'. Check your statement.');
        } catch (\Throwable $e) {
            log_message('error', 'Payment callback error: '.$e->getMessage());
            return redirect()->to('/dashboard')->with('info', 'Payment is being processed. Check your statement in a few minutes.');
        }
    }

    public function ipn()
    {
        $orderTrackingId = $this->request->getGet('OrderTrackingId') ?? $this->request->getPost('OrderTrackingId');
        $merchantRef     = $this->request->getGet('OrderMerchantReference') ?? $this->request->getPost('OrderMerchantReference');
        log_message('info', 'PesaPal IPN: '.$orderTrackingId);
        if (! $orderTrackingId) return $this->response->setStatusCode(400)->setBody('Missing OrderTrackingId');
        try {
            $status = $this->pesapal->handleIpn($orderTrackingId);
            $this->processPaymentStatus($orderTrackingId, $merchantRef, $status);
        } catch (\Throwable $e) { log_message('error', 'IPN error: '.$e->getMessage()); }
        return $this->response->setStatusCode(200)->setBody('OK');
    }

    private function processPaymentStatus(string $orderTrackingId, ?string $merchantRef, array $status): void
    {
        $db      = \Config\Database::connect();
        $pending = $db->table('pending_payments')->where('order_tracking_id', $orderTrackingId)->get()->getRowArray();
        if (! $pending) { log_message('warning', 'No pending payment for '.$orderTrackingId); return; }
        $db->table('pending_payments')->where('order_tracking_id', $orderTrackingId)->update([
            'pesapal_status'=>$status['payment_status_description']??null,
            'confirmation_code'=>$status['confirmation_code']??null,
            'payment_method'=>$status['payment_method']??null,
            'status'=>strtolower($status['payment_status_description']??'pending'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        if (($status['payment_status_description']??'') !== 'Completed') return;
        if ($db->table('transactions')->where('reference', $merchantRef)->countAllResults() > 0) return;
        $currentBalance = $this->transactions->getCurrentBalance($pending['user_id']);
        $newBalance     = $currentBalance + $pending['amount'];
        $this->transactions->insert([
            'member_id'=>$pending['user_id'],'type'=>'credit',
            'amount'=>$pending['amount'],'description'=>$pending['description'],
            'reference'=>$merchantRef,'balance_after'=>$newBalance,'created_by'=>null,
        ]);
        $user = $this->users->find($pending['user_id']);
        if ($user) {
            try {
                (new EmailService())->sendPaymentConfirmation($user, [
                    'amount'=>$pending['amount'],'reference'=>$merchantRef,'paid_at'=>date('Y-m-d H:i:s'),
                    'description'=>$pending['description'],'method'=>$status['payment_method']??'PesaPal',
                    'pesapal_transaction_id'=>$orderTrackingId,'balance_after'=>$newBalance,
                ]);
            } catch (\Throwable $e) {}
        }
    }
}
