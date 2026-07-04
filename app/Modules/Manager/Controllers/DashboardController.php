<?php
declare(strict_types=1);
namespace App\Modules\Manager\Controllers;
use App\Controllers\BaseController;
class DashboardController extends BaseController
{
    public function index(): \CodeIgniter\HTTP\RedirectResponse { return redirect()->to('/dashboard'); }
}
