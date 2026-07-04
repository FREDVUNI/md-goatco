<?php
/**
 * MD Goatco Farm Limited — Front Controller
 *
 * If your folder structure differs from the default (e.g. cPanel shared
 * hosting where public_html is a separate folder), edit the paths in
 * app/Config/Paths.php — NOT this file.
 */

use CodeIgniter\Boot;
use Config\Paths;

$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    die('PHP '.$minPhpVersion.' or higher required. You have '.PHP_VERSION);
}

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

require FCPATH . '../app/Config/Paths.php';

$paths = new Paths();

if (! is_dir($paths->systemDirectory)) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo "CodeIgniter framework not found in vendor/. Did you run 'composer install'?";
    exit(1);
}

require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'Boot.php';

exit(Boot::bootWeb($paths));
