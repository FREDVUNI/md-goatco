<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table      = 'payments';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'member_id',
        'merchant_reference',
        'order_tracking_id',
        'amount',
        'currency',
        'description',
        'status',            // pending | completed | failed | invalid | reversed
        'payment_method',
        'confirmation_code',
        'raw_response',
        'transaction_id',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $beforeInsert = ['generateReference'];

    // ── Finders ───────────────────────────────────────────────────────────────

    public function findByReference(string $merchantReference): ?array
    {
        return $this->where('merchant_reference', $merchantReference)->first();
    }

    public function findByOrderTrackingId(string $orderTrackingId): ?array
    {
        return $this->where('order_tracking_id', $orderTrackingId)->first();
    }

    public function getByMember(int $memberId, int $limit = 50): array
    {
        return $this->where('member_id', $memberId)
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);
    }

    public function getAllRecent(int $limit = 100): array
    {
        return $this->select('payments.*, users.first_name, users.last_name, users.email')
            ->join('users', 'users.id = payments.member_id')
            ->orderBy('payments.created_at', 'DESC')
            ->findAll($limit);
    }

    // ── Status transitions ────────────────────────────────────────────────────

    public function attachOrderTrackingId(int $paymentId, string $orderTrackingId): void
    {
        $this->update($paymentId, ['order_tracking_id' => $orderTrackingId]);
    }

    public function markCompleted(int $paymentId, array $pesapalData, int $transactionId): void
    {
        $this->update($paymentId, [
            'status'            => 'completed',
            'payment_method'    => $pesapalData['payment_method'] ?? null,
            'confirmation_code' => $pesapalData['confirmation_code'] ?? null,
            'raw_response'      => json_encode($pesapalData),
            'transaction_id'    => $transactionId,
        ]);
    }

    public function markStatus(int $paymentId, string $status, array $pesapalData = []): void
    {
        $data = ['status' => $status];

        if (! empty($pesapalData)) {
            $data['payment_method']    = $pesapalData['payment_method'] ?? null;
            $data['confirmation_code'] = $pesapalData['confirmation_code'] ?? null;
            $data['raw_response']      = json_encode($pesapalData);
        }

        $this->update($paymentId, $data);
    }

    // ── Callback ──────────────────────────────────────────────────────────────

    protected function generateReference(array $data): array
    {
        if (empty($data['data']['merchant_reference'])) {
            $data['data']['merchant_reference'] = 'GBANK-' . strtoupper(bin2hex(random_bytes(5)));
        }

        return $data;
    }
}
