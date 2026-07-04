<?php
declare(strict_types=1);
namespace Config;
use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    // ── Change this to your domain before going live ──────────────────────
    public string $baseURL = 'http://localhost:8080/';
    public array  $allowedHostnames = [];

    public string $indexPage     = '';
    public string $uriProtocol   = 'REQUEST_URI';
    public string $permittedURIChars = 'a-z 0-9~%.:_\-';
    public string $defaultLocale = 'en';
    public bool   $negotiateLocale = false;
    public array  $supportedLocales = ['en'];
    public string $appTimezone  = 'Africa/Kampala';
    public string $charset      = 'UTF-8';
    public bool   $forceGlobalSecureRequests = false;
    public array  $proxyIPs     = [];
    public bool   $CSPEnabled   = false;
}
