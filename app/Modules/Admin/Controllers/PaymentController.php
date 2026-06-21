<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Models\PaymentModel;

/**
 * Admin PaymentController
 *
 * Read-only oversight of all Pesapal wallet top-ups across every member —
 * useful for reconciliation and for spotting failed payments members may
 * need help with.
 */
class PaymentController extends BaseController
{
    public function index(): string
    {
        $payments = new PaymentModel();

        $all = $payments->getAllRecent(200);

        return $this->dashboardView('admin/payments', [
            'pageTitle' => 'Wallet Top-ups',
            'payments'  => $all,
            'totals'    => [
                'completed' => count(array_filter($all, fn ($p) => $p['status'] === 'completed')),
                'pending'   => count(array_filter($all, fn ($p) => $p['status'] === 'pending')),
                'failed'    => count(array_filter($all, fn ($p) => in_array($p['status'], ['failed', 'invalid', 'reversed'], true))),
                'sum'       => array_sum(array_map(fn ($p) => $p['status'] === 'completed' ? (int) $p['amount'] : 0, $all)),
            ],
        ]);
    }
}
