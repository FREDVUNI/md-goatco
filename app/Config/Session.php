<?php
declare(strict_types=1);
namespace Config;
use CodeIgniter\Config\BaseConfig;

class Session extends BaseConfig
{
    public string $driver      = 'CodeIgniter\\Session\\Handlers\\FileHandler';
    public string $cookieName  = 'mdgoatco_session';
    public int    $expiration  = 7200;
    public string $savePath    = WRITEPATH . 'session';
    public bool   $matchIP     = false;
    public int    $timeToUpdate= 300;
    public bool   $regenerateDestroy = false;
    public string $cookieDomain   = '';
    public string $cookiePath     = '/';
    public bool   $cookieSecure   = false;
    public bool   $cookieHTTPOnly = true;
    public string $cookieSameSite = 'Lax';
}
