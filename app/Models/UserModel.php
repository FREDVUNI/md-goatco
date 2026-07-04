<?php
declare(strict_types=1);
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['email','password_hash','role','status','first_name','last_name','phone','last_login_at','reset_token','reset_token_expires_at','deleted_at'];
    protected $useTimestamps = true;

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_BCRYPT, ['cost'=>12]);
            unset($data['data']['password']);
        }
        return $data;
    }

    public function verifyPassword(string $plain, string $hash): bool { return password_verify($plain, $hash); }

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->where('deleted_at', null)->first();
    }

    public function findByResetToken(string $token): ?array
    {
        return $this->where('reset_token', $token)->where('reset_token_expires_at >', date('Y-m-d H:i:s'))->first();
    }

    public function setResetToken(int $id, string $token): void
    {
        $this->update($id, ['reset_token'=>$token,'reset_token_expires_at'=>date('Y-m-d H:i:s', strtotime('+1 hour'))]);
    }

    public function clearResetToken(int $id): void { $this->update($id, ['reset_token'=>null,'reset_token_expires_at'=>null]); }

    public function updateLastLogin(int $id): void { $this->update($id, ['last_login_at'=>date('Y-m-d H:i:s')]); }

    public function getByRole(string $role): array { return $this->where('role',$role)->where('deleted_at',null)->findAll(); }

    public function getStaff(): array
    {
        return $this->whereIn('role',['manager','vet','super_admin'])->where('deleted_at',null)->findAll();
    }

    public function getStaffQuery(?string $search = null): \CodeIgniter\Database\BaseBuilder
    {
        $this->select('id,email,role,status,first_name,last_name,phone,last_login_at,created_at')
            ->whereIn('role',['manager','vet','super_admin'])->where('deleted_at',null)->orderBy('created_at','DESC');
        if ($search) {
            $this->groupStart()->like('first_name',$search)->orLike('last_name',$search)->orLike('email',$search)->groupEnd();
        }
        return $this->builder();
    }

    public function countByRole(): array
    {
        $rows = $this->select('role, COUNT(*) as cnt')->where('deleted_at',null)->groupBy('role')->findAll();
        $out  = [];
        foreach ($rows as $r) $out[$r['role']] = $r['cnt'];
        return $out;
    }

    public function activate(int $id): void   { $this->update($id, ['status'=>'active']); }
    public function deactivate(int $id): void { $this->update($id, ['status'=>'inactive']); }
}
