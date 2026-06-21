<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

// ══════════════════════════════════════════════════════════════════════════════
// TRANSACTION MODEL
// ══════════════════════════════════════════════════════════════════════════════

class TransactionModel extends Model
{
    protected $table      = 'transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'member_id',     // FK → users.id
        'type',          // credit | debit
        'amount',        // in UGX (integer)
        'description',
        'reference',     // TXN-XXXX
        'balance_after',
        'created_by',    // admin/system user_id
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = null; // transactions are immutable

    protected $beforeInsert = ['generateReference'];

    // ── Finders ───────────────────────────────────────────────────────────────

    public function getByMember(int $memberId, int $limit = 50): array
    {
        return $this->where('member_id', $memberId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }

    public function getCurrentBalance(int $memberId): int
    {
        $latest = $this->select('balance_after')
                       ->where('member_id', $memberId)
                       ->orderBy('created_at', 'DESC')
                       ->first();

        return $latest ? (int) $latest['balance_after'] : 0;
    }

    public function getTotalCredited(int $memberId): int
    {
        $result = $this->selectSum('amount', 'total')
                       ->where('member_id', $memberId)
                       ->where('type', 'credit')
                       ->first();

        return (int) ($result['total'] ?? 0);
    }

    public function getTotalDebited(int $memberId): int
    {
        $result = $this->selectSum('amount', 'total')
                       ->where('member_id', $memberId)
                       ->where('type', 'debit')
                       ->first();

        return (int) ($result['total'] ?? 0);
    }

    /**
     * Credit a member's wallet — computes the new running balance and
     * inserts the ledger row in one step. Used by the Pesapal top-up flow
     * once a payment is confirmed COMPLETED.
     *
     * @return int|false The new transaction's ID, or false on failure.
     */
    public function credit(int $memberId, int $amount, string $description, ?int $createdBy = null): int|false
    {
        $newBalance = $this->getCurrentBalance($memberId) + $amount;

        $id = $this->insert([
            'member_id'     => $memberId,
            'type'          => 'credit',
            'amount'        => $amount,
            'description'   => $description,
            'balance_after' => $newBalance,
            'created_by'    => $createdBy,
        ]);

        return $id ?: false;
    }

    // ── Callback ──────────────────────────────────────────────────────────────

    protected function generateReference(array $data): array
    {
        if (empty($data['data']['reference'])) {
            $data['data']['reference'] = 'TXN-' . strtoupper(bin2hex(random_bytes(4)));
        }

        return $data;
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// NOTIFICATION MODEL
// ══════════════════════════════════════════════════════════════════════════════

class NotificationModel extends Model
{
    protected $table      = 'notifications';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        'title',
        'body',
        'type',      // info | success | warning | alert
        'link',      // optional in-app link
        'is_read',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = null;

    // ── Finders ───────────────────────────────────────────────────────────────

    public function getForUser(int $userId, int $limit = 30): array
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }

    public function getUnreadCount(int $userId): int
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }

    public function markRead(int $id, int $userId): bool
    {
        return $this->where('id', $id)
                    ->where('user_id', $userId)
                    ->set(['is_read' => 1])
                    ->update();
    }

    public function markAllRead(int $userId): bool
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->set(['is_read' => 1])
                    ->update();
    }

    // ── Factory helpers ───────────────────────────────────────────────────────

    public function notifyUser(int $userId, string $title, string $body, string $type = 'info', string $link = ''): bool
    {
        return $this->insert([
            'user_id' => $userId,
            'title'   => $title,
            'body'    => $body,
            'type'    => $type,
            'link'    => $link,
            'is_read' => 0,
        ]) !== false;
    }

    public function notifyAllAdmins(string $title, string $body, string $type = 'info'): void
    {
        $userModel = new UserModel();
        $admins    = $userModel->getByRole('super_admin');

        foreach ($admins as $admin) {
            $this->notifyUser($admin['id'], $title, $body, $type);
        }
    }
}
