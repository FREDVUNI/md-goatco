<?php

namespace Config;

use CodeIgniter\Modules\Modules as BaseModules;

/**
 * @immutable
 */
class Modules extends BaseModules
{
    public $enabled = true;
    public $discoverInComposer = true;
    public $composerPackages = [];

    public $aliases = [
        'events',
        'filters',
        'registrars',
        'routes',
        'services',
    ];
}
