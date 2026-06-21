<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    // ─── URLs ────────────────────────────────────────────────────────────────

    /**
     * URL to your CodeIgniter root. Typically, this will be your base URL,
     * WITH a trailing slash. Set via .env: app.baseURL
     */
    public string $baseURL = 'http://localhost:8080/';

    /**
     * Allowed hostnames — prevent host-header injection.
     * Add your production domain(s) here.
     *
     * @var list<string>
     */
    public array $allowedHostnames = [];

    /**
     * No index.php in URLs (relies on the .htaccess rewrite rule).
     */
    public string $indexPage = '';

    public string $uriProtocol = 'REQUEST_URI';

    /*
    |--------------------------------------------------------------------------
    | Allowed URL Characters
    |--------------------------------------------------------------------------
    | DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
    */
    public string $permittedURIChars = 'a-z 0-9~%.:_\-';

    // ─── LOCALE ──────────────────────────────────────────────────────────────
    public string $defaultLocale    = 'en';
    public bool   $negotiateLocale  = false;
    public array  $supportedLocales = ['en'];

    /**
     * Application timezone — used for date helpers and app_timezone().
     */
    public string $appTimezone = 'Africa/Kampala';

    // ─── CHARSET ─────────────────────────────────────────────────────────────
    public string $charset = 'UTF-8';

    // ─── SECURITY ────────────────────────────────────────────────────────────
    public bool $forceGlobalSecureRequests = false; // set true in production (HTTPS only)

    // ─── CONTENT SECURITY POLICY ─────────────────────────────────────────────
    public bool $CSPEnabled = false;

    // ─── REVERSE PROXY ───────────────────────────────────────────────────────
    /**
     * @var array<string, string>
     */
    public array $proxyIPs = [];
}
