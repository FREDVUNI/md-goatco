<?php

declare(strict_types=1);

namespace App\Modules\Api\Controllers\V1;

use App\Libraries\PesapalLibrary;
use App\Models\PaymentModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * PAYMENT API — wallet top-ups via Pesapal, for the mobile app.
 *
 * The mobile app opens `redirect_url` in an in-app browser/webview; once
 * the member finishes paying, Pesapal's IPN (handled by the web
 * PaymentController) credits the wallet exactly the same way as the web
 * flow. The app can poll GET /payments/:reference to show live status.
 */
class PaymentApiController extends BaseApiController
{
    public function initiateTopup(): ResponseInterface
    {
        $body   = $this->request->getJSON(true) ?? [];
        $amount = (int) ($body['amount'] ?? 0);

        $pesapalConfig = config('Pesapal');

        if ($amount < $pesapalConfig->minTopupAmount || $amount > $pesapalConfig->maxTopupAmount) {
            return $this->error(sprintf(
                'Amount must be between %s and %s.',
                number_format($pesapalConfig->minTopupAmount),
                number_format($pesapalConfig->maxTopupAmount)
            ));
        }

        $user = (new UserModel())->find($this->authUserId());
        if (! $user) {
            return $this->unauthorized();
        }

        $payments  = new PaymentModel();
        $paymentId = $payments->insert([
            'member_id'   => $user['id'],
            'amount'      => $amount,
            'currency'    => $pesapalConfig->currency,
            'description' => 'Goat Banking wallet top-up',
            'status'      => 'pending',
        ]);

        if (! $paymentId) {
            return $this->error('Could not start the top-up. Please try again.', 500);
        }

        $payment = $payments->find($paymentId);

        try {
            $client = new PesapalLibrary();
            $order  = $client->submitOrder([
                'merchant_reference' => $payment['merchant_reference'],
                'amount'             => $amount,
                'description'        => 'Goat Banking wallet top-up',
                'email'              => $user['email'],
                'first_name'         => $user['first_name'],
                'last_name'          => $user['last_name'],
            ]);

            $payments->attachOrderTrackingId($paymentId, $order['order_tracking_id']);

            return $this->ok([
                'merchant_reference' => $payment['merchant_reference'],
                'redirect_url'       => $order['redirect_url'],
            ], 'Open redirect_url to complete payment.');

        } catch (\Throwable $e) {
            log_message('error', 'Pesapal submitOrder (API) failed for ' . $payment['merchant_reference'] . ': ' . $e->getMessage());
            $payments->markStatus($paymentId, 'failed');

            return $this->error('Could not reach Pesapal. Please try again shortly.', 502);
        }
    }

    public function status(string $merchantReference): ResponseInterface
    {
        $payments = new PaymentModel();
        $payment  = $payments->findByReference($merchantReference);

        if (! $payment || (int) $payment['member_id'] !== $this->authUserId()) {
            return $this->notFound('Top-up not found.');
        }

        return $this->ok([
            'merchant_reference' => $payment['merchant_reference'],
            'amount'             => (int) $payment['amount'],
            'currency'           => $payment['currency'],
            'status'             => $payment['status'], // pending|completed|failed|invalid|reversed
            'created_at'         => $payment['created_at'],
        ]);
    }
}
