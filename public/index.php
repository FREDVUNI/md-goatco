<?php

/**
 * MD Goatco Farm Limited
 * CodeIgniter 4 Front Controller
 *
 * For cPanel shared hosting deployment:
 *   - This file lives in public_html/ (or your subdomain's document root)
 *   - The rest of the app lives one level ABOVE public_html/
 *     e.g. /home/username/mdgoatco/
 *
 * If your folder structure differs from the default, edit the paths in
 * app/Config/Paths.php (NOT this file) — see the comments in that file.
 */

use CodeIgniter\Boot;
use Config\Paths;

// ── ENSURE MINIMUM PHP VERSION ──────────────────────────────────────
$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run MD Goatco Farm. Current version: %s',
        $minPhpVersion,
        PHP_VERSION
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;
    exit(1);
}

// ── SET THE CURRENT DIRECTORY ─────────────────────────────────────────
// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// ── BOOTSTRAP THE APPLICATION ───────────────────────────────────────
// This process sets up the path constants, loads and registers our
// autoloader, along with Composer's, loads our constants, and fires
// up an environment-specific bootstrapping (reads .env, defines the
// ENVIRONMENT constant, etc.)

// LOAD OUR PATHS CONFIG FILE
// (this is the file to edit if you move the app/ or vendor/ folders —
// e.g. for cPanel shared hosting where public_html is a separate folder)
require FCPATH . '../app/Config/Paths.php';

$paths = new Paths();

if (! is_dir($paths->systemDirectory)) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo "CodeIgniter framework not found in vendor/. Did you run 'composer install'?";
    exit(1);
}

// LOAD THE FRAMEWORK BOOTSTRAP FILE
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'Boot.php';

exit(Boot::bootWeb($paths));
