<?php
declare(strict_types=1);
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index(): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/dashboard');
    }
}
