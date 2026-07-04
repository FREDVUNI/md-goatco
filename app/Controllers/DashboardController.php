<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\GoatModel;
use App\Models\UserModel;
use App\Models\MemberApplicationModel;
use App\Models\TransactionModel;
use App\Models\NotificationModel;
use App\Models\VetVisitModel;
use Config\Database;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $role   = $this->currentUserRole();
        $userId = $this->currentUserId();

        $notifs = new NotificationModel();
        $common = [
            'notifications' => $notifs->getForUser($userId, 10),
            'unreadCount'   => $notifs->getUnreadCount($userId),
        ];

        $roleData = match ($role) {
            'super_admin' => $this->adminData(),
            'manager'     => $this->managerData(),
            'vet'         => $this->vetData($userId),
            default       => $this->memberData($userId),
        };

        return $this->dashboardView('dashboard/index', array_merge($common, $roleData));
    }

    /**
     * Monthly counts for the last $months months, keyed 'Y-m', from $table
     * filtered by $dateColumn and any extra WHERE clause.
     */
    private function monthlySeries(string $table, string $dateColumn, ?string $where = null, int $months = 6): array
    {
        $db      = Database::connect();
        $builder = $db->table($table)
            ->select("DATE_FORMAT($dateColumn, '%Y-%m') as period, COUNT(*) as cnt")
            ->where("$dateColumn >=", date('Y-m-01', strtotime('-' . ($months - 1) . ' months')));
        if ($where) $builder->where($where);
        $rows = $builder->groupBy('period')->orderBy('period','ASC')->get()->getResultArray();

        $byPeriod = [];
        foreach ($rows as $r) $byPeriod[$r['period']] = (int) $r['cnt'];

        $labels = [];
        $values = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $key = date('Y-m', strtotime("-$i months"));
            $labels[] = date('M', strtotime($key . '-01'));
            $values[] = $byPeriod[$key] ?? 0;
        }
        return [$labels, $values];
    }

    /** Weekly counts for the last $weeks weeks, keyed by ISO year-week. */
    private function weeklySeries(string $table, string $dateColumn, ?string $where = null, int $weeks = 6): array
    {
        $db      = Database::connect();
        $builder = $db->table($table)
            ->select("YEARWEEK($dateColumn, 3) as period, COUNT(*) as cnt")
            ->where("$dateColumn >=", date('Y-m-d', strtotime('-' . ($weeks - 1) . ' weeks')));
        if ($where) $builder->where($where);
        $rows = $builder->groupBy('period')->orderBy('period','ASC')->get()->getResultArray();

        $byPeriod = [];
        foreach ($rows as $r) $byPeriod[(string) $r['period']] = (int) $r['cnt'];

        $labels = [];
        $values = [];
        for ($i = $weeks - 1; $i >= 0; $i--) {
            $ts  = strtotime("-$i weeks");
            $key = date('o', $ts) . date('W', $ts);
            $labels[] = 'Wk ' . date('W', $ts);
            $values[] = $byPeriod[$key] ?? 0;
        }
        return [$labels, $values];
    }

    private function adminData(): array
    {
        $users        = new UserModel();
        $applications = new MemberApplicationModel();
        $goats        = new GoatModel();
        [$appLabels, $appValues] = $this->monthlySeries('member_applications', 'created_at');
        return [
            'pageTitle'     => 'Dashboard Overview',
            'totalMembers'  => count($users->getByRole('member')),
            'pendingCount'  => $applications->countPending(),
            'goatStats'     => $goats->getStats(),
            'recentPending' => $applications->getPending(),
            'staffCounts'   => $users->countByRole(),
            'appLabels'     => $appLabels,
            'appValues'     => $appValues,
        ];
    }

    private function managerData(): array
    {
        $goatModel  = new GoatModel();
        $visitModel = new VetVisitModel();
        $userModel  = new UserModel();
        $db         = Database::connect();
        $todayTasks = $db->table('vet_schedules')
            ->where('DATE(scheduled_at)', date('Y-m-d'))
            ->orderBy('scheduled_at', 'ASC')
            ->get()->getResultArray();
        $activeFlags = $visitModel->getActiveFlags();
        [$flagLabels, $flagValues] = $this->monthlySeries('vet_visits', 'visit_date', 'is_flagged = 1');
        return [
            'pageTitle'      => 'Manager Dashboard',
            'herdStats'      => $goatModel->getStats(),
            'flagCount'      => count($activeFlags),
            'activeFlags'    => array_slice($activeFlags, 0, 5),
            'memberCount'    => count($userModel->getByRole('member')),
            'todayTasks'     => $todayTasks,
            'todayTaskCount' => count($todayTasks),
            'flagLabels'     => $flagLabels,
            'flagValues'     => $flagValues,
        ];
    }

    private function vetData(int $vetId): array
    {
        $visits  = new VetVisitModel();
        $myFlags = $visits->getMyActiveFlags($vetId);
        [$visitLabels, $visitValues] = $this->weeklySeries('vet_visits', 'visit_date', "vet_id = $vetId");
        return [
            'pageTitle'    => 'My Dashboard',
            'myFlags'      => $myFlags,
            'recentVisits' => array_slice($visits->getByVet($vetId), 0, 10),
            'flagCount'    => count($myFlags),
            'visitLabels'  => $visitLabels,
            'visitValues'  => $visitValues,
        ];
    }

    private function memberData(int $memberId): array
    {
        $goats        = new GoatModel();
        $transactions = new TransactionModel();
        $myGoats      = $goats->getWithLatestWeight($memberId);

        $db = Database::connect();
        $weightRows = $db->table('weight_logs wl')
            ->select("DATE_FORMAT(wl.logged_at, '%Y-%m') as period, AVG(wl.weight_kg) as avg_weight")
            ->join('goats g', 'g.id = wl.goat_id')
            ->where('g.member_id', $memberId)
            ->where('wl.logged_at >=', date('Y-m-01', strtotime('-5 months')))
            ->groupBy('period')->orderBy('period','ASC')->get()->getResultArray();
        $byPeriod = [];
        foreach ($weightRows as $r) $byPeriod[$r['period']] = round((float) $r['avg_weight'], 1);
        $weightLabels = [];
        $weightValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $key = date('Y-m', strtotime("-$i months"));
            $weightLabels[] = date('M', strtotime($key . '-01'));
            $weightValues[] = $byPeriod[$key] ?? 0;
        }

        return [
            'pageTitle'     => 'My Dashboard',
            'goats'         => $myGoats,
            'goatCount'     => count($myGoats),
            'healthyCount'  => count(array_filter($myGoats, fn($g) => empty($g['is_flagged']))),
            'balance'       => $transactions->getCurrentBalance($memberId),
            'totalCredited' => $transactions->getTotalCredited($memberId),
            'weightLabels'  => $weightLabels,
            'weightValues'  => $weightValues,
        ];
    }
}
