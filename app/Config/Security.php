<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Security Configuration
 *
 * CSRF protection settings for all form submissions.
 * The API routes are excluded via the Filters config (CSRF not applied to /api/*).
 */
class Security extends BaseConfig
{
    /**
     * CSRF protection method.
     * 'cookie'  — token stored in a cookie (default, works well with SPAs)
     * 'session' — token stored in the session (more secure for traditional forms)
     */
    public string $csrfProtection = 'session';

    /**
     * Regenerate CSRF token on every submission?
     * true = more secure but breaks browser back-button on some flows.
     * false = token persists for the session (recommended for multi-step forms).
     */
    public bool $regenerate = false;

    /**
     * Randomize the CSRF token on every request.
     * Leave false to avoid invalidating tokens across multiple open tabs.
     */
    public bool $tokenRandomize = false;

    /**
     * CSRF token name (hidden field name in forms: <?= csrf_field() ?>).
     */
    public string $tokenName = 'csrf_token';

    /**
     * CSRF header name (used by AJAX requests via X-CSRF-Token header).
     */
    public string $headerName = 'X-CSRF-Token';

    /**
     * CSRF cookie name (when using 'cookie' protection method).
     */
    public string $cookieName = 'csrf_cookie';

    /**
     * CSRF token expiry in seconds. 0 = expires with the session.
     */
    public int $expires = 7200;

    /**
     * CSRF cookie SameSite attribute.
     * 'Strict' | 'Lax' | 'None' | ''
     */
    public string $samesite = 'Lax';

    /**
     * Redirect to previous page on CSRF failure (for web forms).
     * false = throw a 403 exception (better for API detection).
     */
    public bool $redirect = true;
}
