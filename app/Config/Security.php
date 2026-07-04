<?php
declare(strict_types=1);
namespace Config;
use CodeIgniter\Config\BaseConfig;

class Security extends BaseConfig
{
    public string $csrfProtection = 'session';
    public bool   $regenerate     = false;
    public bool   $tokenRandomize = false;
    public string $tokenName      = 'csrf_token';
    public string $headerName     = 'X-CSRF-Token';
    public string $cookieName     = 'csrf_cookie';
    public int    $expires        = 7200;
    public string $samesite       = 'Lax';
    public bool   $redirect       = true;
}
