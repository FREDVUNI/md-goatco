<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Pesapal payment gateway configuration.
 *
 * Get your consumer key/secret from the Pesapal developer portal:
 *   Sandbox: https://developer.pesapal.com
 *   Live:    https://www.pesapal.com (Merchant Account → API settings)
 *
 * Override everything in .env, e.g.:
 *   pesapal.consumerKey    = your_key
 *   pesapal.consumerSecret = your_secret
 *   pesapal.environment    = sandbox   # sandbox | live
 */
class Pesapal extends BaseConfig
{
    public string $consumerKey    = '';
    public string $consumerSecret = '';

    /**
     * 'sandbox' for testing (cybqa.pesapal.com), 'live' for production
     * (pay.pesapal.com). Get sandbox test credentials from Pesapal before
     * going live.
     */
    public string $environment = 'sandbox';

    /**
     * Default currency for wallet top-ups.
     */
    public string $currency = 'UGX';

    /**
     * Optional: a manually pre-registered IPN ID. Leave blank and
     * PesapalLibrary will auto-register one (using this app's
     * payments/ipn URL) on first use and cache it.
     */
    public string $ipnId = '';

    /**
     * Minimum/maximum top-up amounts members can submit, in UGX.
     */
    public int $minTopupAmount = 5000;
    public int $maxTopupAmount = 50000000;

    public function baseUrl(): string
    {
        return $this->environment === 'live'
            ? 'https://pay.pesapal.com/v3'
            : 'https://cybqa.pesapal.com/pesapalv3';
    }
}
