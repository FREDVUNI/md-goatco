<?php
declare(strict_types=1);
namespace App\Models;
use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table         = 'transactions';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['member_id','type','amount','description','reference','balance_after','created_by'];

    public function getByMember(int $memberId): array { return $this->where('member_id',$memberId)->orderBy('created_at','DESC')->findAll(); }

    public function getByMemberQuery(int $memberId, ?string $search = null): \CodeIgniter\Database\BaseBuilder
    {
        $this->where('member_id',$memberId)->orderBy('created_at','DESC');
        if ($search) {
            $this->groupStart()->like('description',$search)->orLike('reference',$search)->groupEnd();
        }
        return $this->builder();
    }

    public function getCurrentBalance(int $memberId): float
    {
        $last = $this->where('member_id',$memberId)->orderBy('created_at','DESC')->first();
        return (float)($last['balance_after'] ?? 0);
    }

    public function getTotalCredited(int $memberId): float
    {
        $result = $this->selectSum('amount')->where('member_id',$memberId)->where('type','credit')->first();
        return (float)($result['amount'] ?? 0);
    }
}
