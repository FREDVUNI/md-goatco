<?php
declare(strict_types=1);
namespace App\Models;
use CodeIgniter\Model;

class MemberApplicationModel extends Model
{
    protected $table         = 'member_applications';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'user_id','first_name','last_name','dob','gender','phone','address','occupation',
        'nid_number','nid_front_path','nid_back_path','photo_path',
        'nok_name','nok_relationship','nok_phone','nok_address','nok_nid_number',
        'nok_nid_front_path','nok_nid_back_path',
        'goats_requested','notes','status','rejection_reason','info_request_note',
        'reviewed_by','reviewed_at',
    ];

    public function getPending(): array   { return $this->where('status','pending')->orderBy('created_at','DESC')->findAll(); }
    public function countPending(): int   { return $this->where('status','pending')->countAllResults(); }
    public function findByUserId(int $id): ?array { return $this->where('user_id',$id)->first(); }
    public function findByEmail(string $email): ?array
    {
        $db = \Config\Database::connect();
        return $db->table('member_applications ma')
            ->select('ma.*')
            ->join('users u','u.id = ma.user_id')
            ->where('u.email',$email)
            ->get()->getRowArray() ?: null;
    }
}
