<?php

declare(strict_types=1);

namespace App\Libraries;

use Config\Pesapal as PesapalConfig;

/**
 * PesapalLibrary
 *
 * Minimal wrapper around the Pesapal API v3 (https://developer.pesapal.com)
 * using CodeIgniter's built-in CURLRequest service — no extra Composer
 * dependency required.
 *
 * Flow:
 *   1. submitOrder()        — create a hosted payment page for an amount
 *   2. getTransactionStatus() — poll/confirm the outcome (also used by the
 *                                callback page AND the IPN endpoint)
 *
 * All methods throw \RuntimeException on failure with a human-readable
 * message — callers should catch this and show a friendly error rather
 * than letting it bubble up as a 500.
 */
class PesapalLibrary
{
    private PesapalConfig $config;
    private string $baseUrl;

    public function __construct()
    {
        $this->config  = config('Pesapal');
        $this->baseUrl = $this->config->baseUrl();
    }

    /**
     * Whether Pesapal credentials have actually been configured.
     * Lets controllers show a friendly "payments not set up yet" message
     * instead of a cryptic API error during local development.
     */
    public function isConfigured(): bool
    {
        return $this->config->consumerKey !== '' && $this->config->consumerSecret !== '';
    }

    // ── Access token ─────────────────────────────────────────────────────────

    /**
     * Fetch (and cache) an OAuth bearer token. Pesapal tokens are short
     * lived (~5 minutes) so we cache for slightly less than that.
     */
    private function getAccessToken(): string
    {
        $cache = service('cache');
        $cached = $cache->get('pesapal_access_token');

        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $response = $this->request('POST', '/api/Auth/RequestToken', [
            'consumer_key'    => $this->config->consumerKey,
            'consumer_secret' => $this->config->consumerSecret,
        ], authenticated: false);

        if (empty($response['token'])) {
            throw new \RuntimeException('Pesapal did not return an access token: ' . ($response['message'] ?? 'unknown error'));
        }

        $cache->save('pesapal_access_token', $response['token'], 270);

        return $response['token'];
    }

    // ── IPN registration ─────────────────────────────────────────────────────

    /**
     * Get a registered IPN ID, registering one with Pesapal if we don't
     * have one cached yet. The IPN URL is built from this app's own
     * baseURL, so it automatically follows whatever app.baseURL is set
     * to in .env — no hardcoding needed.
     *
     * NOTE: Pesapal's IPN is a server-to-server callback, so the URL
     * must be publicly reachable. On `localhost` this registration will
     * still succeed, but Pesapal's servers will never actually be able
     * to reach it — that's fine, because the payment callback page
     * (reachable by the member's own browser) independently confirms
     * and credits the payment too, so local development still works
     * end-to-end without a public IPN endpoint.
     */
    public function getIpnId(): string
    {
        if ($this->config->ipnId !== '') {
            return $this->config->ipnId;
        }

        $cache    = service('cache');
        $cacheKey = 'pesapal_ipn_id_' . $this->config->environment;
        $cached   = $cache->get($cacheKey);

        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $response = $this->request('POST', '/api/URLSetup/RegisterIPN', [
            'url'                   => site_url('payments/ipn'),
            'ipn_notification_type' => 'GET',
        ]);

        if (empty($response['ipn_id'])) {
            throw new \RuntimeException('Pesapal IPN registration failed: ' . ($response['message'] ?? 'unknown error'));
        }

        // Cache for a year — only needs to be re-registered if the domain changes.
        $cache->save($cacheKey, $response['ipn_id'], YEAR);

        return $response['ipn_id'];
    }

    // ── Submit order ──────────────────────────────────────────────────────────

    /**
     * Create a Pesapal order and return the hosted payment page details.
     *
     * @param array $order {
     *     merchant_reference: string,
     *     amount: int,
     *     description: string,
     *     email: string,
     *     phone?: string,
     *     first_name?: string,
     *     last_name?: string,
     * }
     * @return array{order_tracking_id: string, redirect_url: string, merchant_reference: string}
     */
    public function submitOrder(array $order): array
    {
        $payload = [
            'id'              => $order['merchant_reference'],
            'currency'        => $this->config->currency,
            'amount'          => $order['amount'],
            'description'     => $order['description'] ?? 'MD Goatco Farm wallet top-up',
            'callback_url'    => site_url('payments/callback'),
            'notification_id' => $this->getIpnId(),
            'billing_address' => [
                'email_address' => $order['email'] ?? '',
                'phone_number'  => $order['phone'] ?? '',
                'country_code'  => 'UG',
                'first_name'    => $order['first_name'] ?? '',
                'last_name'     => $order['last_name'] ?? '',
            ],
        ];

        $response = $this->request('POST', '/api/Transactions/SubmitOrderRequest', $payload);

        if (! empty($response['error'])) {
            $message = is_array($response['error']) ? ($response['error']['message'] ?? 'Order rejected') : $response['error'];
            throw new \RuntimeException('Pesapal rejected the order: ' . $message);
        }

        if (empty($response['redirect_url']) || empty($response['order_tracking_id'])) {
            throw new \RuntimeException('Pesapal did not return a redirect URL for this order.');
        }

        return [
            'order_tracking_id'  => $response['order_tracking_id'],
            'redirect_url'       => $response['redirect_url'],
            'merchant_reference' => $response['merchant_reference'] ?? $order['merchant_reference'],
        ];
    }

    // ── Transaction status ───────────────────────────────────────────────────

    /**
     * Look up the current status of an order from Pesapal.
     *
     * @return array{
     *   status_description: string,  COMPLETED | FAILED | INVALID | REVERSED | PENDING
     *   amount: float,
     *   confirmation_code: string,
     *   payment_method: string,
     *   merchant_reference: string,
     *   raw: array
     * }
     */
    public function getTransactionStatus(string $orderTrackingId): array
    {
        $response = $this->request(
            'GET',
            '/api/Transactions/GetTransactionStatus?orderTrackingId=' . rawurlencode($orderTrackingId)
        );

        if (! empty($response['error'])) {
            $message = is_array($response['error']) ? ($response['error']['message'] ?? 'Status check failed') : $response['error'];
            throw new \RuntimeException('Pesapal status check failed: ' . $message);
        }

        return [
            'status_description' => strtoupper($response['payment_status_description'] ?? 'PENDING'),
            'amount'              => (float) ($response['amount'] ?? 0),
            'confirmation_code'   => (string) ($response['confirmation_code'] ?? ''),
            'payment_method'      => (string) ($response['payment_method'] ?? ''),
            'merchant_reference'  => (string) ($response['merchant_reference'] ?? ''),
            'raw'                 => $response,
        ];
    }

    // ── HTTP plumbing ─────────────────────────────────────────────────────────

    /**
     * Make a request against the Pesapal API. GET requests send $body as
     * a pre-built query string in $path; POST requests send $body as JSON.
     */
    private function request(string $method, string $path, array $body = [], bool $authenticated = true): array
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('Pesapal is not configured. Set pesapal.consumerKey and pesapal.consumerSecret in your .env file.');
        }

        $client = service('curlrequest', [
            'http_errors' => false,
            'timeout'     => 30,
        ]);

        $headers = ['Accept' => 'application/json'];

        if ($authenticated) {
            $headers['Authorization'] = 'Bearer ' . $this->getAccessToken();
        }

        try {
            if ($method === 'GET') {
                $response = $client->get($this->baseUrl . $path, ['headers' => $headers]);
            } else {
                $headers['Content-Type'] = 'application/json';
                $response = $client->post($this->baseUrl . $path, [
                    'headers' => $headers,
                    'json'    => $body,
                ]);
            }
        } catch (\Throwable $e) {
            throw new \RuntimeException('Could not reach Pesapal: ' . $e->getMessage());
        }

        $statusCode = $response->getStatusCode();
        $raw        = $response->getBody();
        $decoded    = json_decode($raw, true);

        if (! is_array($decoded)) {
            throw new \RuntimeException("Pesapal returned an unexpected response (HTTP {$statusCode}).");
        }

        if ($statusCode >= 400) {
            $message = $decoded['message'] ?? ($decoded['error']['message'] ?? "HTTP {$statusCode}");
            throw new \RuntimeException('Pesapal API error: ' . $message);
        }

        return $decoded;
    }
}
