<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'email',
        'password_hash',
        'role',           // super_admin | manager | vet | member
        'status',         // active | inactive | pending | rejected
        'first_name',
        'last_name',
        'phone',
        'created_by',     // user_id of the admin who created this staff account
        'last_login_at',
        'password_reset_token',
        'password_reset_expires',
        'refresh_token',  // for JWT refresh
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Never return password in results by default
    protected $hiddenFields = ['password_hash', 'password_reset_token', 'refresh_token'];

    protected $validationRules = [
        'email'         => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password_hash' => 'required',
        'role'          => 'required|in_list[super_admin,manager,vet,member]',
        'status'        => 'required|in_list[active,inactive,pending,rejected]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'That email address is already registered.',
        ],
    ];

    protected $beforeInsert = ['hashPasswordIfSet'];
    protected $beforeUpdate = ['hashPasswordIfSet'];

    // ── Finders ───────────────────────────────────────────────────────────────

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)
                    ->where('deleted_at', null)
                    ->first();
    }

    public function findActiveByEmail(string $email): ?array
    {
        return $this->where('email', $email)
                    ->where('status', 'active')
                    ->where('deleted_at', null)
                    ->first();
    }

    public function getByRole(string $role): array
    {
        return $this->where('role', $role)
                    ->where('deleted_at', null)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getStaff(): array
    {
        return $this->whereIn('role', ['manager', 'vet'])
                    ->where('deleted_at', null)
                    ->orderBy('role', 'ASC')
                    ->orderBy('first_name', 'ASC')
                    ->findAll();
    }

    // ── Auth helpers ──────────────────────────────────────────────────────────

    public function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public function updateLastLogin(int $userId): void
    {
        $this->update($userId, ['last_login_at' => date('Y-m-d H:i:s')]);
    }

    public function setResetToken(int $userId, string $token): void
    {
        $this->update($userId, [
            'password_reset_token'   => hash('sha256', $token),
            'password_reset_expires' => date('Y-m-d H:i:s', strtotime('+1 hour')),
        ]);
    }

    public function findByResetToken(string $token): ?array
    {
        return $this->where('password_reset_token', hash('sha256', $token))
                    ->where('password_reset_expires >', date('Y-m-d H:i:s'))
                    ->where('deleted_at', null)
                    ->first();
    }

    public function clearResetToken(int $userId): void
    {
        $this->update($userId, [
            'password_reset_token'   => null,
            'password_reset_expires' => null,
        ]);
    }

    public function activate(int $userId): void
    {
        $this->update($userId, ['status' => 'active']);
    }

    public function deactivate(int $userId): void
    {
        $this->update($userId, ['status' => 'inactive']);
    }

    // ── Callbacks ─────────────────────────────────────────────────────────────

    protected function hashPasswordIfSet(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash(
                $data['data']['password'],
                PASSWORD_BCRYPT,
                ['cost' => 12]
            );
            unset($data['data']['password']);
        }

        return $data;
    }

    // ── Stats (for admin dashboard) ───────────────────────────────────────────

    public function countByRole(): array
    {
        $results = $this->select('role, COUNT(*) as total')
                        ->where('deleted_at', null)
                        ->groupBy('role')
                        ->findAll();

        return array_column($results, 'total', 'role');
    }
}
