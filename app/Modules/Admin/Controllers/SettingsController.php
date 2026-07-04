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
}
