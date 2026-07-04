<?php
declare(strict_types=1);
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;

class PaymentController extends BaseController
{
    private function paymentsQuery(?string $search): \CodeIgniter\Database\BaseBuilder
    {
        $builder = \Config\Database::connect()->table('payments p')
            ->select('p.*, p.merchant_reference as reference, u.first_name, u.last_name, u.email')
            ->join('users u','u.id=p.member_id','left')
            ->orderBy('p.created_at','DESC');
        if ($search) {
            $builder->groupStart()->like('p.merchant_reference',$search)->orLike('u.first_name',$search)->orLike('u.last_name',$search)->orLike('u.email',$search)->groupEnd();
        }
        return $builder;
    }

    public function index(): string
    {
        $search = $this->searchTerm();
        [$payments, $pager] = $this->paginateBuilder($this->paymentsQuery($search));

        return $this->dashboardView('admin/payments', [
            'pageTitle' => 'Payments',
            'payments'  => $payments,
            'pager'     => $pager,
            'search'    => $search,
        ]);
    }

    public function export()
    {
        $rows = $this->paymentsQuery($this->searchTerm())->get()->getResultArray();
        return $this->downloadCsv($rows, 'payments_' . date('Y-m-d') . '.csv');
    }
}
