<?php
declare(strict_types=1);
namespace Config;
use CodeIgniter\Config\BaseConfig;
use App\Filters\GuestFilter;
use App\Filters\RoleFilter;
use App\Filters\JwtFilter;
use App\Filters\CorsFilter;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'     => \CodeIgniter\Filters\CSRF::class,
        'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot' => \CodeIgniter\Filters\Honeypot::class,
        'guest'    => GuestFilter::class,
        'role'     => RoleFilter::class,
        'jwt'      => JwtFilter::class,
        'cors'     => CorsFilter::class,
    ];
    public array $globals = [
        'before' => ['csrf' => ['except' => ['api/v1/*', 'payments/ipn']]],
        'after'  => [],
    ];
    public array $methods  = [];
    public array $filters  = [];
}
