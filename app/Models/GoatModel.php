<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

// ══════════════════════════════════════════════════════════════════════════════
// GOAT MODEL
// ══════════════════════════════════════════════════════════════════════════════

class GoatModel extends Model
{
    protected $table      = 'goats';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'tag_number', 'name', 'breed', 'sex', 'dob',
        'member_id',   // FK → users.id (nullable — unassigned goats)
        'pen_id',
        'status',      // active | sold | deceased | transferred
        'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // ── Finders ───────────────────────────────────────────────────────────────

    public function getByMember(int $memberId): array
    {
        return $this->where('member_id', $memberId)
                    ->where('status', 'active')
                    ->where('deleted_at', null)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    public function getWithLatestWeight(int $memberId): array
    {
        return $this->select('goats.*, wl.weight_kg as latest_weight, wl.logged_at as weight_date')
                    ->join(
                        '(SELECT goat_id, weight_kg, logged_at FROM weight_logs ORDER BY logged_at DESC) wl',
                        'wl.goat_id = goats.id',
                        'left'
                    )
                    ->where('goats.member_id', $memberId)
                    ->where('goats.status', 'active')
                    ->where('goats.deleted_at', null)
                    ->groupBy('goats.id')
                    ->findAll();
    }

    public function getFullHerd(): array
    {
        return $this->select('goats.*, users.first_name, users.last_name,
                              wl.weight_kg as latest_weight, vv.is_flagged, vv.flag_reason')
                    ->join('users', 'users.id = goats.member_id', 'left')
                    ->join(
                        '(SELECT goat_id, weight_kg FROM weight_logs ORDER BY logged_at DESC) wl',
                        'wl.goat_id = goats.id',
                        'left'
                    )
                    ->join(
                        '(SELECT goat_id, is_flagged, flag_reason FROM vet_visits
                          WHERE is_flagged = 1 AND flag_resolved_at IS NULL
                          ORDER BY created_at DESC) vv',
                        'vv.goat_id = goats.id',
                        'left'
                    )
                    ->where('goats.deleted_at', null)
                    ->where('goats.status', 'active')
                    ->groupBy('goats.id')
                    ->orderBy('goats.name', 'ASC')
                    ->findAll();
    }

    public function findByTag(string $tag): ?array
    {
        return $this->where('tag_number', $tag)
                    ->where('deleted_at', null)
                    ->first();
    }

    public function countFlagged(): int
    {
        return $this->db->table('goats g')
                        ->join('vet_visits vv', 'vv.goat_id = g.id')
                        ->where('vv.is_flagged', 1)
                        ->where('vv.flag_resolved_at', null)
                        ->where('g.deleted_at', null)
                        ->countAllResults();
    }

    public function getStats(): array
    {
        return [
            'total'    => $this->where('status', 'active')->where('deleted_at', null)->countAllResults(),
            'flagged'  => $this->countFlagged(),
            'in_banking' => $this->where('member_id IS NOT NULL')
                                 ->where('status', 'active')
                                 ->where('deleted_at', null)
                                 ->countAllResults(),
        ];
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// VET VISIT MODEL
// ══════════════════════════════════════════════════════════════════════════════

class VetVisitModel extends Model
{
    protected $table      = 'vet_visits';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'goat_id', 'vet_id', 'visit_type',
        'visit_date', 'temperature', 'weight_kg',
        'medication', 'clinical_notes',
        'is_flagged', 'flag_reason', 'followup_date', 'flag_resolved_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $afterInsert = ['logWeightIfPresent'];

    // ── Finders ───────────────────────────────────────────────────────────────

    public function getByGoat(int $goatId): array
    {
        return $this->select('vet_visits.*, users.first_name, users.last_name')
                    ->join('users', 'users.id = vet_visits.vet_id')
                    ->where('vet_visits.goat_id', $goatId)
                    ->orderBy('vet_visits.visit_date', 'DESC')
                    ->findAll();
    }

    public function getActiveFlags(): array
    {
        return $this->select('vet_visits.*, goats.tag_number, goats.name as goat_name,
                              users.first_name as vet_first, users.last_name as vet_last,
                              m.first_name as member_first, m.last_name as member_last')
                    ->join('goats', 'goats.id = vet_visits.goat_id')
                    ->join('users', 'users.id = vet_visits.vet_id')
                    ->join('users m', 'm.id = goats.member_id', 'left')
                    ->where('vet_visits.is_flagged', 1)
                    ->where('vet_visits.flag_resolved_at', null)
                    ->orderBy('vet_visits.created_at', 'DESC')
                    ->findAll();
    }

    public function getByVet(int $vetId): array
    {
        return $this->select('vet_visits.*, goats.tag_number, goats.name as goat_name')
                    ->join('goats', 'goats.id = vet_visits.goat_id')
                    ->where('vet_visits.vet_id', $vetId)
                    ->orderBy('vet_visits.visit_date', 'DESC')
                    ->findAll();
    }

    public function getMyActiveFlags(int $vetId): array
    {
        return $this->select('vet_visits.*, goats.tag_number, goats.name as goat_name')
                    ->join('goats', 'goats.id = vet_visits.goat_id')
                    ->where('vet_visits.vet_id', $vetId)
                    ->where('vet_visits.is_flagged', 1)
                    ->where('vet_visits.flag_resolved_at', null)
                    ->orderBy('vet_visits.created_at', 'DESC')
                    ->findAll();
    }

    public function resolveFlag(int $visitId): bool
    {
        return $this->update($visitId, ['flag_resolved_at' => date('Y-m-d H:i:s')]);
    }

    // ── Callback — auto-log weight when a visit includes it ──────────────────

    protected function logWeightIfPresent(array $data): array
    {
        if (! empty($data['data']['weight_kg']) && ! empty($data['id'])) {
            $visit = $this->find($data['id']);
            if ($visit) {
                $weightModel = new WeightLogModel();
                $weightModel->insert([
                    'goat_id'   => $visit['goat_id'],
                    'weight_kg' => $visit['weight_kg'],
                    'logged_by' => $visit['vet_id'],
                    'logged_at' => $visit['visit_date'],
                ]);
            }
        }

        return $data;
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// WEIGHT LOG MODEL
// ══════════════════════════════════════════════════════════════════════════════

class WeightLogModel extends Model
{
    protected $table      = 'weight_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'goat_id', 'weight_kg', 'logged_by', 'logged_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = null;

    public function getByGoat(int $goatId, int $limit = 12): array
    {
        return $this->where('goat_id', $goatId)
                    ->orderBy('logged_at', 'DESC')
                    ->findAll($limit);
    }

    public function getLatestForGoat(int $goatId): ?array
    {
        return $this->where('goat_id', $goatId)
                    ->orderBy('logged_at', 'DESC')
                    ->first();
    }

    public function getGrowthChart(int $goatId): array
    {
        // Returns month-by-month average weight for charting
        return $this->select("
                DATE_FORMAT(logged_at, '%Y-%m') as month,
                AVG(weight_kg) as avg_weight,
                MAX(weight_kg) as max_weight
            ")
            ->where('goat_id', $goatId)
            ->groupBy("DATE_FORMAT(logged_at, '%Y-%m')")
            ->orderBy('month', 'ASC')
            ->findAll();
    }
}
