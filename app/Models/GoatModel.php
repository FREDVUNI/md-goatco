<?php
declare(strict_types=1);
namespace App\Models;
use CodeIgniter\Model;

class GoatModel extends Model
{
    protected $table         = 'goats';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['tag_number','name','breed','sex','dob','pen_id','member_id','status','notes'];

    public function getStats(): array
    {
        $db    = \Config\Database::connect();
        $total = $db->table('goats')->where('status','active')->countAllResults();
        $banking = $db->table('goats')->where('status','active')->where('member_id IS NOT NULL')->countAllResults();
        $flagged = $db->table('goats g')
            ->join('vet_visits v','v.goat_id = g.id AND v.is_flagged = 1 AND v.flag_resolved_at IS NULL','left')
            ->where('g.status','active')->where('v.id IS NOT NULL')->countAllResults();
        return ['total'=>$total,'in_banking'=>$banking,'flagged'=>$flagged];
    }

    public function getFullHerd(): array
    {
        return \Config\Database::connect()->table('goats g')
            ->select('g.*, u.first_name, u.last_name, u.id as member_id,
                      (SELECT weight_kg FROM weight_logs WHERE goat_id=g.id ORDER BY logged_at DESC LIMIT 1) as latest_weight,
                      (SELECT COUNT(*) FROM vet_visits WHERE goat_id=g.id AND is_flagged=1 AND flag_resolved_at IS NULL) as is_flagged')
            ->join('users u','u.id = g.member_id','left')
            ->where('g.status','active')
            ->orderBy('g.tag_number','ASC')
            ->get()->getResultArray();
    }

    public function getWithLatestWeight(int $memberId): array
    {
        return \Config\Database::connect()->table('goats g')
            ->select('g.*,
                      (SELECT weight_kg FROM weight_logs WHERE goat_id=g.id ORDER BY logged_at DESC LIMIT 1) as latest_weight,
                      (SELECT logged_at FROM weight_logs WHERE goat_id=g.id ORDER BY logged_at DESC LIMIT 1) as weight_date,
                      (SELECT COUNT(*) FROM vet_visits WHERE goat_id=g.id AND is_flagged=1 AND flag_resolved_at IS NULL) as is_flagged')
            ->where('g.member_id',$memberId)->where('g.status','active')
            ->get()->getResultArray();
    }

    public function getByMember(int $memberId): array { return $this->where('member_id',$memberId)->where('status','active')->findAll(); }

    public function getFullHerdQuery(?string $search = null): \CodeIgniter\Database\BaseBuilder
    {
        $builder = \Config\Database::connect()->table('goats g')
            ->select('g.*, u.first_name, u.last_name, u.id as member_id,
                      (SELECT weight_kg FROM weight_logs WHERE goat_id=g.id ORDER BY logged_at DESC LIMIT 1) as latest_weight,
                      (SELECT COUNT(*) FROM vet_visits WHERE goat_id=g.id AND is_flagged=1 AND flag_resolved_at IS NULL) as is_flagged')
            ->join('users u','u.id = g.member_id','left')
            ->where('g.status','active')
            ->orderBy('g.tag_number','ASC');
        if ($search) {
            $builder->groupStart()->like('g.tag_number',$search)->orLike('g.name',$search)->orLike('g.breed',$search)->groupEnd();
        }
        return $builder;
    }

    public function getWithLatestWeightQuery(int $memberId, ?string $search = null): \CodeIgniter\Database\BaseBuilder
    {
        $builder = \Config\Database::connect()->table('goats g')
            ->select('g.*,
                      (SELECT weight_kg FROM weight_logs WHERE goat_id=g.id ORDER BY logged_at DESC LIMIT 1) as latest_weight,
                      (SELECT logged_at FROM weight_logs WHERE goat_id=g.id ORDER BY logged_at DESC LIMIT 1) as weight_date,
                      (SELECT COUNT(*) FROM vet_visits WHERE goat_id=g.id AND is_flagged=1 AND flag_resolved_at IS NULL) as is_flagged')
            ->where('g.member_id',$memberId)->where('g.status','active')
            ->orderBy('g.tag_number','ASC');
        if ($search) {
            $builder->groupStart()->like('g.tag_number',$search)->orLike('g.name',$search)->groupEnd();
        }
        return $builder;
    }
}
