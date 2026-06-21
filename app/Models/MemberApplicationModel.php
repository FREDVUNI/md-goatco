<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class MemberApplicationModel extends Model
{
    protected $table      = 'member_applications';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        // Personal
        'first_name', 'last_name', 'dob', 'gender',
        'phone', 'address', 'occupation',
        // ID documents
        'nid_number', 'nid_front_path', 'nid_back_path', 'photo_path',
        // Next of kin
        'nok_name', 'nok_relationship', 'nok_phone', 'nok_address',
        'nok_nid_number', 'nok_nid_front_path', 'nok_nid_back_path',
        // Banking preferences
        'goats_requested', 'notes',
        // Review
        'status',          // pending | approved | rejected | info_requested
        'reviewed_by',     // user_id of admin
        'reviewed_at',
        'rejection_reason',
        'info_request_note',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ── Finders ───────────────────────────────────────────────────────────────

    public function getPending(): array
    {
        return $this->where('status', 'pending')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    public function getWithUser(int $id): ?array
    {
        return $this->select('member_applications.*, users.email')
                    ->join('users', 'users.id = member_applications.user_id', 'left')
                    ->where('member_applications.id', $id)
                    ->first();
    }

    public function findByUserId(int $userId): ?array
    {
        return $this->where('user_id', $userId)->first();
    }

    public function findByEmail(string $email): ?array
    {
        return $this->select('member_applications.*, users.email, users.status as account_status')
                    ->join('users', 'users.id = member_applications.user_id')
                    ->where('users.email', $email)
                    ->first();
    }

    // ── Status transitions ────────────────────────────────────────────────────

    public function approve(int $id, int $reviewedBy): bool
    {
        return $this->update($id, [
            'status'      => 'approved',
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function reject(int $id, int $reviewedBy, string $reason = ''): bool
    {
        return $this->update($id, [
            'status'           => 'rejected',
            'reviewed_by'      => $reviewedBy,
            'reviewed_at'      => date('Y-m-d H:i:s'),
            'rejection_reason' => $reason,
        ]);
    }

    public function requestInfo(int $id, int $reviewedBy, string $note): bool
    {
        return $this->update($id, [
            'status'            => 'info_requested',
            'reviewed_by'       => $reviewedBy,
            'reviewed_at'       => date('Y-m-d H:i:s'),
            'info_request_note' => $note,
        ]);
    }

    // ── Counts ────────────────────────────────────────────────────────────────

    public function countPending(): int
    {
        return $this->where('status', 'pending')->countAllResults();
    }
}
