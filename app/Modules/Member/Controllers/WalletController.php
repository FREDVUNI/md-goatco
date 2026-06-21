<?php

declare(strict_types=1);

namespace App\Modules\Member\Controllers;

use App\Controllers\BaseController;
use App\Libraries\PesapalLibrary;
use App\Models\PaymentModel;
use App\Models\TransactionModel;

/**
 * WalletController
 *
 * Lets a member top up their Goat Banking wallet via Pesapal (cards,
 * mobile money, bank transfer — whatever Pesapal has enabled on the
 * merchant account). The actual crediting happens in PaymentController
 * once Pesapal confirms the payment — this controller only ever creates
 * a 'pending' record and sends the member to Pesapal's hosted page.
 */
class WalletController extends BaseController
{
    private PaymentModel     $payments;
    private TransactionModel $transactions;

    public function __construct()
    {
        $this->payments     = new PaymentModel();
        $this->transactions = new TransactionModel();
    }

    /**
     * Top-up form + recent top-up history.
     */
    public function topup(): string
    {
        $memberId = $this->currentUserId();
        $pesapal  = config('Pesapal');

        return $this->dashboardView('member/wallet_topup', [
            'pageTitle'  => 'Top Up Wallet',
            'balance'    => $this->transactions->getCurrentBalance($memberId),
            'history'    => $this->payments->getByMember($memberId, 10),
            'minAmount'  => $pesapal->minTopupAmount,
            'maxAmount'  => $pesapal->maxTopupAmount,
            'configured' => $pesapal->consumerKey !== '' && $pesapal->consumerSecret !== '',
        ]);
    }

    /**
     * Create the payment record and redirect the member to Pesapal.
     */
    public function initiateTopup()
    {
        $pesapal = config('Pesapal');

        $rules = [
            'amount' => 'required|is_natural_no_zero|greater_than_equal_to[' . $pesapal->minTopupAmount . ']|less_than_equal_to[' . $pesapal->maxTopupAmount . ']',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/member/wallet/topup')->withInput()->with('errors', $this->validator->getErrors());
        }

        $amount = (int) $this->request->getPost('amount');
        $user   = $this->currentUser();

        $paymentId = $this->payments->insert([
            'member_id'   => $user['id'],
            'amount'      => $amount,
            'currency'    => $pesapal->currency,
            'description' => 'Goat Banking wallet top-up',
            'status'      => 'pending',
        ]);

        if (! $paymentId) {
            return redirect()->to('/member/wallet/topup')->with('error', 'Could not start the top-up. Please try again.');
        }

        $payment = $this->payments->find($paymentId);

        try {
            $pesapalClient = new PesapalLibrary();
            $order = $pesapalClient->submitOrder([
                'merchant_reference' => $payment['merchant_reference'],
                'amount'             => $amount,
                'description'        => 'Goat Banking wallet top-up',
                'email'              => $user['email'],
                'phone'              => '',
                'first_name'         => $user['first_name'],
                'last_name'          => $user['last_name'],
            ]);

            $this->payments->attachOrderTrackingId($paymentId, $order['order_tracking_id']);

            return redirect()->to($order['redirect_url']);

        } catch (\Throwable $e) {
            log_message('error', 'Pesapal submitOrder failed for payment ' . $payment['merchant_reference'] . ': ' . $e->getMessage());
            $this->payments->markStatus($paymentId, 'failed');

            return redirect()->to('/member/wallet/topup')
                             ->with('error', 'We could not reach Pesapal to start your payment. Please try again shortly.');
        }
    }

    /**
     * Status page for a specific top-up attempt — shown after returning
     * from Pesapal, or reachable any time from the top-up history list.
     */
    public function topupStatus(string $merchantReference): string
    {
        $payment = $this->payments->findByReference($merchantReference);

        if (! $payment || (int) $payment['member_id'] !== $this->currentUserId()) {
            return $this->dashboardView('member/wallet_topup_status', [
                'pageTitle' => 'Top-up not found',
                'payment'   => null,
            ]);
        }

        return $this->dashboardView('member/wallet_topup_status', [
            'pageTitle' => 'Top-up Status',
            'payment'   => $payment,
        ]);
    }
}
