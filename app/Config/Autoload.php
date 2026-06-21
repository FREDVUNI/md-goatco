<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * Autoload Configuration
 *
 * Registers:
 *   - PSR-4 namespaces for modules
 *   - Helper files loaded on every request
 */
class Autoload extends AutoloadConfig
{
    /**
     * PSR-4 namespace → directory mappings.
     * Module controllers live here so CI4's router can find them.
     */
    public $psr4 = [
        APP_NAMESPACE        => APPPATH,
        'Config'             => APPPATH . 'Config',
        // Role modules
        'App\Modules\Admin'   => APPPATH . 'Modules/Admin',
        'App\Modules\Manager' => APPPATH . 'Modules/Manager',
        'App\Modules\Vet'     => APPPATH . 'Modules/Vet',
        'App\Modules\Member'  => APPPATH . 'Modules/Member',
        'App\Modules\Api'     => APPPATH . 'Modules/Api',
    ];

    /**
     * Helpers loaded on every request — no need to call helper() manually.
     */
    public $helpers = [
        'url',
        'form',
        'html',
        'text',
        'goatco',   // Our custom helper: goatAge(), formatUgx(), statusBadge(), etc.
    ];
}
