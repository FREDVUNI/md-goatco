<?php
declare(strict_types=1);
namespace App\Modules\Manager\Controllers;
use App\Controllers\BaseController;
use App\Models\GoatModel;
class ReportController extends BaseController
{
    /**
     * Monthly counts for the last $months months, keyed 'Y-m', from $table
     * filtered by $dateColumn and any extra WHERE clause.
     */
    private function monthlySeries(string $table, string $dateColumn, ?string $where = null, int $months = 6): array
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($table)
            ->select("DATE_FORMAT($dateColumn, '%Y-%m') as month, COUNT(*) as cnt")
            ->where("$dateColumn >=", date('Y-m-01', strtotime('-' . ($months - 1) . ' months')));
        if ($where) $builder->where($where);
        $rows = $builder->groupBy('month')->orderBy('month','ASC')->get()->getResultArray();

        $byMonth = [];
        foreach ($rows as $r) $byMonth[$r['month']] = (int) $r['cnt'];

        $labels = [];
        $values = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $key = date('Y-m', strtotime("-$i months"));
            $labels[] = date('M', strtotime($key . '-01'));
            $values[] = $byMonth[$key] ?? 0;
        }
        return [$labels, $values];
    }

    public function index(): string
    {
        $goats = new GoatModel();
        $db    = \Config\Database::connect();

        [$herdLabels, $herdValues]       = $this->monthlySeries('goats', 'created_at');
        [$healthLabels, $healthValues]   = $this->monthlySeries('vet_visits', 'visit_date', 'is_flagged = 1');
        [$memberLabels, $memberValues]   = $this->monthlySeries('users', 'created_at', "role = 'member'");

        return $this->dashboardView('manager/reports', [
            'pageTitle' => 'Reports',
            'stats'     => array_merge($goats->getStats(), [
                'births_this_month'    => $db->table('goats')->where("MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())")->countAllResults(),
                'mortality_this_month' => $db->table('goats')->where('status','deceased')->where("MONTH(updated_at)=MONTH(NOW())")->countAllResults(),
            ]),
            'herdLabels'    => $herdLabels,
            'herdValues'    => $herdValues,
            'healthLabels'  => $healthLabels,
            'healthValues'  => $healthValues,
            'memberLabels'  => $memberLabels,
            'memberValues'  => $memberValues,
        ]);
    }
    public function herd()   { return redirect()->to('/manager/reports'); }
    public function health() { return redirect()->to('/manager/reports'); }
    public function members(){ return redirect()->to('/manager/reports'); }
    public function export(string $type)
    {
        $db   = \Config\Database::connect();
        $data = match($type) {
            'herd'    => $db->table('goats g')->select('g.tag_number,g.name,g.breed,g.sex,g.pen_id,u.first_name,u.last_name')->join('users u','u.id=g.member_id','left')->where('g.status','active')->get()->getResultArray(),
            'members' => $db->table('users')->select('id,email,phone,status,first_name,last_name,last_login_at,created_at')->where('role','member')->where('deleted_at',null)->get()->getResultArray(),
            'health'  => (new \App\Models\VetVisitModel())->getActiveFlagsQuery()->get()->getResultArray(),
            default   => [],
        };
        return $this->downloadCsv($data, 'mdgoatco_'.$type.'_'.date('Y-m-d').'.csv');
    }
}
