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

    private function adminData(): array
    {
        $users        = new UserModel();
        $applications = new MemberApplicationModel();
        $goats        = new GoatModel();
        return [
            'pageTitle'     => 'Dashboard Overview',
            'totalMembers'  => count($users->getByRole('member')),
            'pendingCount'  => $applications->countPending(),
            'goatStats'     => $goats->getStats(),
            'recentPending' => $applications->getPending(),
            'staffCounts'   => $users->countByRole(),
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
        return [
            'pageTitle'      => 'Manager Dashboard',
            'herdStats'      => $goatModel->getStats(),
            'flagCount'      => count($activeFlags),
            'activeFlags'    => array_slice($activeFlags, 0, 5),
            'memberCount'    => count($userModel->getByRole('member')),
            'todayTasks'     => $todayTasks,
            'todayTaskCount' => count($todayTasks),
        ];
    }

    private function vetData(int $vetId): array
    {
        $visits  = new VetVisitModel();
        $myFlags = $visits->getMyActiveFlags($vetId);
        return [
            'pageTitle'    => 'My Dashboard',
            'myFlags'      => $myFlags,
            'recentVisits' => array_slice($visits->getByVet($vetId), 0, 10),
            'flagCount'    => count($myFlags),
        ];
    }

    private function memberData(int $memberId): array
    {
        $goats        = new GoatModel();
        $transactions = new TransactionModel();
        $myGoats      = $goats->getWithLatestWeight($memberId);
        return [
            'pageTitle'     => 'My Dashboard',
            'goats'         => $myGoats,
            'goatCount'     => count($myGoats),
            'healthyCount'  => count(array_filter($myGoats, fn($g) => empty($g['is_flagged']))),
            'balance'       => $transactions->getCurrentBalance($memberId),
            'totalCredited' => $transactions->getTotalCredited($memberId),
        ];
    }
}
