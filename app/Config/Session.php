<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Session Configuration
 *
 * CI4 sessions are used for all web dashboard authentication.
 * JWT is used separately for the REST API (/api/v1/*).
 *
 * On shared cPanel hosting, the default FileHandler works well.
 * For production with multiple servers, switch to DatabaseHandler.
 */
class Session extends BaseConfig
{
    /**
     * Session driver.
     *
     * Options:
     *   CodeIgniter\Session\Handlers\FileHandler       — default, works on shared hosting
     *   CodeIgniter\Session\Handlers\DatabaseHandler   — recommended for multi-server setups
     *   CodeIgniter\Session\Handlers\MemcachedHandler  — high-performance option
     *   CodeIgniter\Session\Handlers\RedisHandler      — high-performance option
     */
    public string $driver = 'CodeIgniter\\Session\\Handlers\\FileHandler';

    /**
     * Session cookie name.
     * Keep short and alphanumeric. Avoid generic names like 'session'.
     */
    public string $cookieName = 'mdgoatco_session';

    /**
     * Session expiry in seconds.
     * 7200 = 2 hours of inactivity before the session expires.
     * Set to 0 for browser-session only (expires when tab closes).
     */
    public int $expiration = 7200;

    /**
     * Save path for FileHandler — should be outside the webroot.
     * CI4 will create this directory if it doesn't exist.
     */
    public string $savePath = WRITEPATH . 'session';

    /**
     * Match session to the client's IP address?
     * false = sessions work across dynamic IPs (mobile users, VPNs).
     * true  = more secure but breaks mobile users who switch towers.
     */
    public bool $matchIP = false;

    /**
     * How often (in seconds) to regenerate the session ID.
     * Prevents session fixation attacks.
     */
    public int $timeToUpdate = 300;

    /**
     * Destroy old session data when regenerating session ID?
     */
    public bool $regenerateDestroy = false;

    /**
     * Session lock retry interval (microseconds) and max retries —
     * used by handlers that support locking to avoid race conditions
     * when multiple requests from the same session arrive concurrently.
     */
    public int $lockRetryInterval = 100_000;
    public int $lockMaxRetries    = 300;

    /**
     * For DatabaseHandler — table name to store sessions.
     */
    public string $DBGroup = 'default';

    /**
     * Session table name (DatabaseHandler only).
     * Create with: php spark session:migration
     */
    public string $savePath_db = 'ci_sessions';

    /**
     * Cookie settings — inherit from CookieConfig but can override here.
     */
    public string $cookieDomain   = '';
    public string $cookiePath     = '/';
    public bool   $cookieSecure   = false; // set true in production (HTTPS only)
    public bool   $cookieHTTPOnly = true;  // JS cannot access session cookie
    public string $cookieSameSite = 'Lax';
}
