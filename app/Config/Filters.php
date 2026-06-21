<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\RoleFilter;
use App\Filters\JwtFilter;
use App\Filters\CorsFilter;
use App\Filters\GuestFilter;

class Filters extends BaseFilters
{
    /**
     * Configured filter aliases.
     *
     * @var array<string, class-string|list<class-string>>
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,

        // Custom filters
        'role'          => RoleFilter::class,   // role:super_admin  role:vet,manager
        'jwt'           => JwtFilter::class,    // API JWT verification
        'cors'          => CorsFilter::class,   // API CORS headers (overrides framework's own Cors filter)
        'guest'         => GuestFilter::class,  // Redirect logged-in users away from login pages
    ];

    /**
     * Special filters that are always applied before/after every request,
     * even for routes that don't exist. Required for core framework
     * functionality (HTTPS enforcement, page cache, debug toolbar, etc).
     * These filters are no-ops unless separately enabled in their own config.
     *
     * @var array{before: list<string>, after: list<string>}
     */
    public array $required = [
        'before' => [
            'forcehttps', // Force Global Secure Requests
            'pagecache',  // Web Page Caching
        ],
        'after' => [
            'pagecache',   // Web Page Caching
            'performance', // Performance Metrics
            'toolbar',     // Debug Toolbar
        ],
    ];

    /**
     * List of filter aliases that are always applied before/after every
     * real route.
     */
    public array $globals = [
        'before' => [
            'invalidchars',
            // 'csrf', — enable in production; disabled here for API routes
        ],
        'after' => [
            'secureheaders',
        ],
    ];

    /**
     * HTTP method-specific filters.
     */
    public array $methods = [];

    /**
     * Route-specific filters.
     */
    public array $filters = [
        'cors' => [
            'before' => ['api/*'],
            'after'  => ['api/*'],
        ],
        'csrf' => [
            'before' => ['auth/*', 'admin/*', 'manager/*', 'vet/*', 'member/*'],
        ],
    ];
}
