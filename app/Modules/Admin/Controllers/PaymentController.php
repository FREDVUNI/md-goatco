<?php
declare(strict_types=1);
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;

class PaymentController extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $payments = $db->table('payments p')
            ->select('p.*, p.merchant_reference as reference, u.first_name, u.last_name, u.email')
            ->join('users u','u.id=p.member_id','left')
            ->orderBy('p.created_at','DESC')
            ->limit(50)
            ->get()->getResultArray();
        return $this->dashboardView('admin/payments', ['pageTitle'=>'Payments','payments'=>$payments]);
    }
}
