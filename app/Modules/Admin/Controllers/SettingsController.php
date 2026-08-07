<?php
declare(strict_types=1);
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;

class SettingsController extends BaseController
{
    public function index(): string
    {
        return $this->dashboardView('admin/settings', [
            'pageTitle'         => 'System Settings',
            'googleClientId'    => (string) env('GOOGLE_CLIENT_ID', ''),
            'googleClientSecretSet' => ! empty(env('GOOGLE_CLIENT_SECRET')),
        ]);
    }
    public function update()
    {
        return redirect()->to('/admin/settings')->with('success','Settings saved.');
    }

    public function updateGoogleAuth()
    {
        $clientId     = trim((string) $this->request->getPost('google_client_id'));
        $clientSecret = trim((string) $this->request->getPost('google_client_secret'));

        if ($clientId === '') {
            return redirect()->to('/admin/settings')->with('error', 'Google Client ID is required to enable Google sign-in.');
        }

        $this->setEnvValue('GOOGLE_CLIENT_ID', $clientId);
        // Leave the existing secret untouched if the field was left blank —
        // the form never echoes the real secret back, so a blank submit
        // should mean "no change", not "clear it".
        if ($clientSecret !== '') {
            $this->setEnvValue('GOOGLE_CLIENT_SECRET', $clientSecret);
        }

        return redirect()->to('/admin/settings')->with('success', 'Google sign-in settings saved. The "Sign in with Google" button will now appear on the login page.');
    }

    public function disableGoogleAuth()
    {
        $this->setEnvValue('GOOGLE_CLIENT_ID', '');
        $this->setEnvValue('GOOGLE_CLIENT_SECRET', '');
        return redirect()->to('/admin/settings')->with('success', 'Google sign-in disabled.');
    }

    /** Rewrites (or appends) a single KEY = 'value' line in .env, leaving everything else untouched. */
    private function setEnvValue(string $key, string $value): void
    {
        $envPath = ROOTPATH . '.env';
        $escaped = addslashes($value);
        $line    = "{$key} = '{$escaped}'";

        $contents = is_file($envPath) ? file_get_contents($envPath) : '';
        $pattern  = '/^' . preg_quote($key, '/') . '\s*=.*$/m';

        if (preg_match($pattern, $contents)) {
            $contents = preg_replace($pattern, $line, $contents);
        } else {
            $contents = rtrim($contents) . "\n{$line}\n";
        }
        file_put_contents($envPath, $contents);

        // Make the new value visible for the rest of THIS request without a restart.
        $_ENV[$key] = $_SERVER[$key] = $value;
        putenv("{$key}={$value}");
    }

    public function updateLogo()
    {
        $file = $this->request->getFile('logo');
        if (! $file || ! $file->isValid()) {
            return redirect()->to('/admin/settings')->with('error', 'Please choose an image to upload.');
        }
        if (strtolower($file->getExtension()) !== 'png' || strpos((string) $file->getMimeType(), 'image/') !== 0) {
            return redirect()->to('/admin/settings')->with('error', 'Logo must be a PNG image.');
        }
        if ($file->getSize() > 2 * 1024 * 1024) {
            return redirect()->to('/admin/settings')->with('error', 'Logo file is too large (max 2 MB).');
        }
        $file->move(FCPATH . 'img', 'logo.png', true);
        return redirect()->to('/admin/settings')->with('success', 'Logo updated.');
    }

    public function clearCache()
    {
        $cleared = cache()->clean();
        return redirect()->to('/admin/settings')->with(
            $cleared ? 'success' : 'error',
            $cleared ? 'Application cache cleared.' : 'Could not clear the cache — check the writable/cache directory is writable.'
        );
    }
}
