<?php
declare(strict_types=1);
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TransactionModel;

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
        return $this->downloadXlsx($rows, 'payments_' . date('Y-m-d') . '.xlsx', 'Payment Transactions');
    }

    /**
     * Bulk-credit/debit member wallets from a spreadsheet — writes to the
     * `transactions` ledger (what Member > Statements/Wallet read from), not
     * the gateway-sourced `payments` table above, which is system-generated.
     */
    public function importTransactions()
    {
        [$rows, $error] = $this->parseUploadedSpreadsheet('file');
        if ($error) return redirect()->back()->with('error', $error);

        $users  = new UserModel();
        $txns   = new TransactionModel();
        $notifs = new \App\Models\NotificationModel();
        $adminId = $this->currentUserId();
        [$created, $errors] = $this->processImportRows($rows, function (array $row) use ($users, $txns, $notifs, $adminId) {
            $email = strtolower(trim($row['member_email'] ?? ''));
            if ($email === '') return 'member_email is required';
            $member = $users->where('email', $email)->where('role', 'member')->first();
            if (! $member) return "member_email not found: $email";

            $type = strtolower(trim($row['type'] ?? ''));
            if (! in_array($type, ['credit', 'debit'], true)) return "type must be credit or debit, got '$type'";

            $amount = (int) ($row['amount'] ?? 0);
            if ($amount <= 0) return 'amount must be a positive number';

            $description = trim($row['description'] ?? '') ?: ucfirst($type) . ' (bulk import)';
            $reference   = trim($row['reference'] ?? '');
            if ($reference === '') $reference = 'IMP-' . strtoupper(bin2hex(random_bytes(4)));
            if ($txns->where('reference', $reference)->first()) return "reference already exists: $reference";

            $balance = $txns->getCurrentBalance((int) $member['id']);
            $newBalance = $type === 'credit' ? $balance + $amount : $balance - $amount;

            $txns->insert([
                'member_id'     => (int) $member['id'],
                'type'          => $type,
                'amount'        => $amount,
                'description'   => $description,
                'reference'     => $reference,
                'balance_after' => $newBalance,
                'created_by'    => $adminId,
            ]);
            $notifs->notifyUser(
                (int) $member['id'],
                $type === 'credit' ? 'Wallet credited' : 'Wallet debited',
                $description.' — UGX '.number_format($amount),
                $type === 'credit' ? 'success' : 'warning',
                'member/statements'
            );
            return true;
        });
        return $this->importRedirect('/admin/payments', $created, $errors);
    }
}
