<?php
declare(strict_types=1);
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;

class SettingsController extends BaseController
{
    public function index(): string
    {
        return $this->dashboardView('admin/settings', ['pageTitle'=>'System Settings']);
    }
    public function update()
    {
        return redirect()->to('/admin/settings')->with('success','Settings saved.');
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
}
