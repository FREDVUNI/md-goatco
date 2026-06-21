<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libraries\Mailer;
use App\Libraries\PesapalLibrary;
use App\Models\NotificationModel;
use App\Models\PaymentModel;
use App\Models\TransactionModel;
use App\Models\UserModel;

/**
 * PaymentController
 *
 * Handles the two endpoints Pesapal talks to directly — NOT behind the
 * session/role filters, since neither the customer's browser (mid-payment,
 * possibly on a different device) nor Pesapal's own servers carry an
 * MD Goatco session:
 *
 *   GET  /payments/callback  — browser redirect back from Pesapal's hosted
 *                              payment page once the customer finishes (or
 *                              cancels). Used purely for UX; we never trust
 *                              its query parameters for the actual outcome.
 *   GET|POST /payments/ipn   — Pesapal's server-to-server notification.
 *                              This is the source of truth for payment status.
 *
 * Both paths converge on settle(), which re-verifies the real status
 * directly with Pesapal's GetTransactionStatus endpoint before crediting
 * anything — so even if someone guesses or replays a callback URL, no
 * money gets credited without Pesapal actually confirming it.
 */
class PaymentController extends BaseController
{
    private PaymentModel     $payments;
    private TransactionModel $transactions;
    private PesapalLibrary   $pesapal;

    public function __construct()
    {
        $this->payments     = new PaymentModel();
        $this->transactions = new TransactionModel();
        $this->pesapal      = new PesapalLibrary();
    }

    /**
     * Browser redirect target after the customer completes/cancels checkout
     * on Pesapal's hosted page.
     */
    public function callback()
    {
        $orderTrackingId = $this->request->getGet('OrderTrackingId') ?: $this->request->getGet('orderTrackingId');
        $merchantRef      = $this->request->getGet('OrderMerchantReference') ?: $this->request->getGet('orderMerchantReference');

        $payment = $this->findPayment($orderTrackingId, $merchantRef);

        if (! $payment) {
            return redirect()->to('/member/dashboard')
                ->with('error', 'We could not find that payment. If you were charged, contact support and quote your reference.');
        }

        if ($orderTrackingId) {
            $this->settle($payment, $orderTrackingId);
        }

        return redirect()->to('/member/wallet/topup/' . $payment['merchant_reference']);
    }

    /**
     * Pesapal's server-to-server IPN. Must always respond with the exact
     * JSON shape Pesapal expects, even if we don't recognise the payment —
     * otherwise Pesapal will keep retrying indefinitely.
     */
    public function ipn()
    {
        $orderTrackingId = $this->request->getGet('OrderTrackingId') ?: $this->request->getGet('orderTrackingId');
        $merchantRef      = $this->request->getGet('OrderMerchantReference') ?: $this->request->getGet('orderMerchantReference');

        $payment = $this->findPayment($orderTrackingId, $merchantRef);

        if ($payment && $orderTrackingId) {
            $this->settle($payment, $orderTrackingId);
        } else {
            log_message('warning', 'Pesapal IPN for unrecognised payment. trackingId=' . $orderTrackingId . ' ref=' . $merchantRef);
        }

        return $this->response->setJSON([
            'orderNotificationType'  => 'IPNCHANGE',
            'orderTrackingId'        => $orderTrackingId ?? '',
            'orderMerchantReference' => $merchantRef ?? '',
            'status'                 => 200,
        ]);
    }

    // ── Shared settlement logic ─────────────────────────────────────────────

    private function findPayment(?string $orderTrackingId, ?string $merchantRef): ?array
    {
        if ($orderTrackingId) {
            $payment = $this->payments->findByOrderTrackingId($orderTrackingId);
            if ($payment) {
                return $payment;
            }
        }

        if ($merchantRef) {
            return $this->payments->findByReference($merchantRef);
        }

        return null;
    }

    /**
     * Re-verify the order with Pesapal and, if newly COMPLETED, credit the
     * member's wallet and notify them. Idempotent — safe to call multiple
     * times for the same payment (the callback AND the IPN both call it).
     */
    private function settle(array $payment, string $orderTrackingId): void
    {
        // Already finalised — nothing to do. Prevents double-crediting if
        // both the IPN and the browser callback arrive for the same payment.
        if (in_array($payment['status'], ['completed', 'failed', 'invalid', 'reversed'], true)) {
            return;
        }

        if (empty($payment['order_tracking_id'])) {
            $this->payments->attachOrderTrackingId($payment['id'], $orderTrackingId);
        }

        try {
            $result = $this->pesapal->getTransactionStatus($orderTrackingId);
        } catch (\Throwable $e) {
            log_message('error', 'Pesapal status check failed for ' . $payment['merchant_reference'] . ': ' . $e->getMessage());
            return;
        }

        $status = strtolower($result['status_description'] ?? 'pending'); // completed|failed|invalid|pending|reversed

        if ($status === 'completed') {
            $this->creditWallet($payment, $result);
        } elseif (in_array($status, ['failed', 'invalid', 'reversed'], true)) {
            $this->payments->markStatus($payment['id'], $status, $result);
            $this->notifyFailure($payment);
        }
        // 'pending' — leave as-is, Pesapal will notify again on change.
    }

    private function creditWallet(array $payment, array $pesapalResult): void
    {
        $txnId = $this->transactions->credit(
            (int) $payment['member_id'],
            (int) $payment['amount'],
            'Pesapal wallet top-up — ' . $payment['merchant_reference']
        );

        if (! $txnId) {
            log_message('error', 'Failed to credit wallet for payment ' . $payment['merchant_reference']);
            return;
        }

        $this->payments->markCompleted($payment['id'], $pesapalResult, $txnId);

        $user = (new UserModel())->find($payment['member_id']);
        if (! $user) {
            return;
        }

        $balance = $this->transactions->getCurrentBalance((int) $payment['member_id']);

        (new Mailer())->send(
            $user['email'],
            'Payment received — MD Goatco Farm',
            'payment_confirmed',
            [
                'firstName' => $user['first_name'],
                'amount'    => (int) $payment['amount'],
                'reference' => $payment['merchant_reference'],
                'balance'   => $balance,
            ],
            $user['first_name'] . ' ' . $user['last_name']
        );

        (new NotificationModel())->notifyUser(
            (int) $user['id'],
            'Wallet top-up received',
            formatUgx((int) $payment['amount']) . ' has been added to your Goat Banking wallet.',
            'success',
            '/member/statements'
        );
    }

    private function notifyFailure(array $payment): void
    {
        $user = (new UserModel())->find($payment['member_id']);
        if (! $user) {
            return;
        }

        (new Mailer())->send(
            $user['email'],
            'Payment unsuccessful — MD Goatco Farm',
            'payment_failed',
            [
                'firstName' => $user['first_name'],
                'amount'    => (int) $payment['amount'],
                'reference' => $payment['merchant_reference'],
            ],
            $user['first_name'] . ' ' . $user['last_name']
        );
    }
}
