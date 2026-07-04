<?php
declare(strict_types=1);
namespace App\Models;
use CodeIgniter\Model;

class VetVisitModel extends Model
{
    protected $table         = 'vet_visits';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'goat_id','vet_id','visit_date','visit_type','clinical_notes',
        'treatment','outcome','weight_recorded','is_flagged','flag_reason',
        'followup_date','flag_resolved_at',
    ];

    public function getByVet(int $vetId): array
    {
        return \Config\Database::connect()->table('vet_visits v')
            ->select('v.*, g.name as goat_name, g.tag_number')
            ->join('goats g','g.id=v.goat_id')
            ->where('v.vet_id',$vetId)
            ->orderBy('v.visit_date','DESC')
            ->get()->getResultArray();
    }

    public function getActiveFlags(): array
    {
        return \Config\Database::connect()->table('vet_visits v')
            ->select('v.*, g.name as goat_name, g.tag_number, g.pen_id,
                      u.first_name as member_first, u.last_name as member_last,
                      uv.first_name as vet_first, uv.last_name as vet_last')
            ->join('goats g','g.id=v.goat_id')
            ->join('users u','u.id=g.member_id','left')
            ->join('users uv','uv.id=v.vet_id','left')
            ->where('v.is_flagged',1)->where('v.flag_resolved_at',null)
            ->orderBy('v.created_at','DESC')
            ->get()->getResultArray();
    }

    public function getMyActiveFlags(int $vetId): array
    {
        return \Config\Database::connect()->table('vet_visits v')
            ->select('v.*, g.name as goat_name, g.tag_number')
            ->join('goats g','g.id=v.goat_id')
            ->where('v.vet_id',$vetId)->where('v.is_flagged',1)->where('v.flag_resolved_at',null)
            ->orderBy('v.created_at','DESC')
            ->get()->getResultArray();
    }

    public function getByVetQuery(int $vetId, ?string $search = null): \CodeIgniter\Database\BaseBuilder
    {
        $builder = \Config\Database::connect()->table('vet_visits v')
            ->select('v.*, g.name as goat_name, g.tag_number')
            ->join('goats g','g.id=v.goat_id')
            ->where('v.vet_id',$vetId)
            ->orderBy('v.visit_date','DESC');
        if ($search) {
            $builder->groupStart()->like('g.tag_number',$search)->orLike('g.name',$search)->orLike('v.visit_type',$search)->groupEnd();
        }
        return $builder;
    }

    public function getActiveFlagsQuery(?string $search = null): \CodeIgniter\Database\BaseBuilder
    {
        $builder = \Config\Database::connect()->table('vet_visits v')
            ->select('v.*, g.name as goat_name, g.tag_number, g.pen_id,
                      u.first_name as member_first, u.last_name as member_last,
                      uv.first_name as vet_first, uv.last_name as vet_last')
            ->join('goats g','g.id=v.goat_id')
            ->join('users u','u.id=g.member_id','left')
            ->join('users uv','uv.id=v.vet_id','left')
            ->where('v.is_flagged',1)->where('v.flag_resolved_at',null)
            ->orderBy('v.created_at','DESC');
        if ($search) {
            $builder->groupStart()->like('g.tag_number',$search)->orLike('g.name',$search)->orLike('v.flag_reason',$search)->groupEnd();
        }
        return $builder;
    }

    public function getMyActiveFlagsQuery(int $vetId, ?string $search = null): \CodeIgniter\Database\BaseBuilder
    {
        $builder = \Config\Database::connect()->table('vet_visits v')
            ->select('v.*, g.name as goat_name, g.tag_number')
            ->join('goats g','g.id=v.goat_id')
            ->where('v.vet_id',$vetId)->where('v.is_flagged',1)->where('v.flag_resolved_at',null)
            ->orderBy('v.created_at','DESC');
        if ($search) {
            $builder->groupStart()->like('g.tag_number',$search)->orLike('g.name',$search)->orLike('v.flag_reason',$search)->groupEnd();
        }
        return $builder;
    }
}
