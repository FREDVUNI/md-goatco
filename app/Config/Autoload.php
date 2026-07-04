<?php
declare(strict_types=1);
namespace Config;
use CodeIgniter\Config\AutoloadConfig;

class Autoload extends AutoloadConfig
{
    public $psr4 = [
        APP_NAMESPACE          => APPPATH,
        'Config'               => APPPATH . 'Config',
        'App\Modules\Admin'    => APPPATH . 'Modules/Admin',
        'App\Modules\Manager'  => APPPATH . 'Modules/Manager',
        'App\Modules\Vet'      => APPPATH . 'Modules/Vet',
        'App\Modules\Member'   => APPPATH . 'Modules/Member',
        'App\Modules\Api'      => APPPATH . 'Modules/Api',
    ];
    public $classmap = [];
    public $files    = [];
    public $helpers  = ['goatco', 'email', 'url', 'form'];
}
