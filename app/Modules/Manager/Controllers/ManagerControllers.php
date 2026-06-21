<?php

declare(strict_types=1);

namespace App\Modules\Manager\Controllers;

use App\Controllers\BaseController;
use App\Models\GoatModel;
use App\Models\VetVisitModel;
use App\Models\UserModel;
use App\Models\NotificationModel;

// ══════════════════════════════════════════════════════════════════════════════
// MANAGER DASHBOARD
// ══════════════════════════════════════════════════════════════════════════════

class DashboardController extends BaseController
{
    public function index(): string
    {
        $goatModel   = new GoatModel();
        $visitModel  = new VetVisitModel();
        $userModel   = new UserModel();
        $notifModel  = new NotificationModel();
        $db          = \Config\Database::connect();

        // Today's vet schedule
        $todayTasks = $db->table('vet_schedules')
                         ->where('DATE(scheduled_at)', date('Y-m-d'))
                         ->orderBy('scheduled_at', 'ASC')
                         ->get()->getResultArray();

        // Recent members with goat count
        $recentMembers = $db->table('users u')
                            ->select('u.*, COUNT(g.id) as goat_count')
                            ->join('goats g', 'g.member_id = u.id', 'left')
                            ->where('u.role', 'member')
                            ->where('u.status', 'active')
                            ->groupBy('u.id')
                            ->orderBy('u.created_at', 'DESC')
                            ->limit(5)
                            ->get()->getResultArray();

        return $this->dashboardView('manager/dashboard', [
            'pageTitle'     => 'Manager Dashboard',
            'herdStats'     => $goatModel->getStats(),
            'flagCount'     => count($visitModel->getActiveFlags()),
            'activeFlags'   => array_slice($visitModel->getActiveFlags(), 0, 5),
            'memberCount'   => count($userModel->getByRole('member')),
            'recentMembers' => $recentMembers,
            'todayTasks'    => $todayTasks,
            'todayTaskCount'=> count($todayTasks),
            'unreadCount'   => $notifModel->getUnreadCount($this->currentUserId()),
            'notifications' => $notifModel->getForUser($this->currentUserId(), 8),
        ]);
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// HERD MANAGEMENT
// ══════════════════════════════════════════════════════════════════════════════

class HerdController extends BaseController
{
    public function index(): string
    {
        $goatModel = new GoatModel();
        return $this->dashboardView('manager/herd', [
            'pageTitle' => 'Herd Registry',
            'herd'      => $goatModel->getFullHerd(),
        ]);
    }

    public function show(int $id): string
    {
        $goatModel  = new GoatModel();
        $visitModel = new VetVisitModel();
        $goat       = $goatModel->find($id);

        if (! $goat) {
            return redirect()->to('/manager/herd')->with('error', 'Animal not found.');
        }

        return $this->dashboardView('manager/goat_detail', [
            'pageTitle' => $goat['name'] . ' — Goat Detail',
            'goat'      => $goat,
            'visits'    => $visitModel->getByGoat($id),
        ]);
    }

    public function create(): string
    {
        return $this->dashboardView('manager/goat_form', ['pageTitle' => 'Add Goat to Herd']);
    }

    public function store()
    {
        $rules = [
            'tag_number' => 'required|is_unique[goats.tag_number]|max_length[50]',
            'name'       => 'required|max_length[100]',
            'breed'      => 'permit_empty|max_length[100]',
            'sex'        => 'required|in_list[male,female]',
            'dob'        => 'permit_empty|valid_date[Y-m-d]',
            'pen_id'     => 'permit_empty|max_length[20]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $goatModel = new GoatModel();
        $goatModel->insert([
            'tag_number' => strtoupper($this->request->getPost('tag_number')),
            'name'       => $this->request->getPost('name'),
            'breed'      => $this->request->getPost('breed'),
            'sex'        => $this->request->getPost('sex'),
            'dob'        => $this->request->getPost('dob') ?: null,
            'pen_id'     => $this->request->getPost('pen_id'),
            'member_id'  => $this->request->getPost('member_id') ?: null,
            'status'     => 'active',
            'notes'      => $this->request->getPost('notes'),
        ]);

        return redirect()->to('/manager/herd')->with('success', 'Animal added to herd.');
    }

    public function edit(int $id): string
    {
        $goatModel = new GoatModel();
        $goat      = $goatModel->find($id);

        if (! $goat) {
            return redirect()->to('/manager/herd')->with('error', 'Animal not found.');
        }

        $userModel = new UserModel();
        return $this->dashboardView('manager/goat_form', [
            'pageTitle' => 'Edit — ' . $goat['name'],
            'goat'      => $goat,
            'members'   => $userModel->getByRole('member'),
        ]);
    }

    public function update(int $id)
    {
        $rules = [
            'name'  => 'required|max_length[100]',
            'breed' => 'permit_empty|max_length[100]',
            'sex'   => 'required|in_list[male,female]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $goatModel = new GoatModel();
        $goatModel->update($id, [
            'name'      => $this->request->getPost('name'),
            'breed'     => $this->request->getPost('breed'),
            'sex'       => $this->request->getPost('sex'),
            'dob'       => $this->request->getPost('dob') ?: null,
            'pen_id'    => $this->request->getPost('pen_id'),
            'member_id' => $this->request->getPost('member_id') ?: null,
            'status'    => $this->request->getPost('status'),
            'notes'     => $this->request->getPost('notes'),
        ]);

        return redirect()->to('/manager/herd')->with('success', 'Animal record updated.');
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// HEALTH FLAGS
// ══════════════════════════════════════════════════════════════════════════════

class HealthController extends BaseController
{
    public function index(): string
    {
        $visitModel = new VetVisitModel();
        return $this->dashboardView('manager/health', [
            'pageTitle' => 'Active Health Flags',
            'flags'     => $visitModel->getActiveFlags(),
        ]);
    }

    public function show(int $id): string
    {
        $visitModel = new VetVisitModel();
        $flag       = $visitModel->find($id);

        if (! $flag) {
            return redirect()->to('/manager/health')->with('error', 'Flag not found.');
        }

        $goatModel = new GoatModel();
        $userModel = new UserModel();

        return $this->dashboardView('manager/flag_detail', [
            'pageTitle' => 'Health Flag Detail',
            'flag'      => $flag,
            'goat'      => $goatModel->find($flag['goat_id']),
            'vet'       => $userModel->find($flag['vet_id']),
        ]);
    }

    public function resolve(int $id)
    {
        $visitModel = new VetVisitModel();
        $visit      = $visitModel->find($id);

        if (! $visit) {
            return redirect()->to('/manager/health')->with('error', 'Flag not found.');
        }

        $visitModel->resolveFlag($id);

        // Notify goat owner
        if (! empty($visit['goat_id'])) {
            $goatModel = new GoatModel();
            $goat      = $goatModel->find($visit['goat_id']);
            if ($goat && $goat['member_id']) {
                $notifModel = new NotificationModel();
                $notifModel->notifyUser(
                    $goat['member_id'],
                    'Health concern resolved: ' . $goat['name'],
                    $goat['name'] . ' has been cleared. Everything is looking good.',
                    'success',
                    '/member/goats/' . $goat['id']
                );
            }
        }

        return redirect()->to('/manager/health')->with('success', 'Flag marked as resolved.');
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// VET SCHEDULE
// ══════════════════════════════════════════════════════════════════════════════

class ScheduleController extends BaseController
{
    private \CodeIgniter\Database\BaseBuilder $scheduleTable;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->scheduleTable = $db->table('vet_schedules');
    }

    public function index(): string
    {
        $tasks = $this->scheduleTable
                      ->select('vet_schedules.*, users.first_name, users.last_name')
                      ->join('users', 'users.id = vet_schedules.assigned_vet_id', 'left')
                      ->orderBy('vet_schedules.scheduled_at', 'ASC')
                      ->get()->getResultArray();

        $userModel = new UserModel();
        return $this->dashboardView('manager/schedule', [
            'pageTitle' => 'Vet Schedule',
            'tasks'     => $tasks,
            'vets'      => $userModel->getByRole('vet'),
        ]);
    }

    public function create(): string
    {
        $userModel = new UserModel();
        return $this->dashboardView('manager/schedule_create', [
            'pageTitle' => 'Add Scheduled Task',
            'vets'      => $userModel->getByRole('vet'),
        ]);
    }

    public function store()
    {
        $rules = [
            'task'         => 'required|min_length[3]|max_length[255]',
            'scheduled_at' => 'required|valid_date[Y-m-d\TH:i]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->scheduleTable->insert([
            'task'            => $this->request->getPost('task'),
            'description'     => $this->request->getPost('description'),
            'animals_desc'    => $this->request->getPost('animals_desc'),
            'assigned_vet_id' => $this->request->getPost('assigned_vet_id') ?: null,
            'scheduled_at'    => $this->request->getPost('scheduled_at'),
            'status'          => 'scheduled',
            'created_by'      => $this->currentUserId(),
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/manager/schedule')->with('success', 'Task scheduled successfully.');
    }

    public function complete(int $id)
    {
        $this->scheduleTable->where('id', $id)->update([
            'status'       => 'completed',
            'completed_at' => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/manager/schedule')->with('success', 'Task marked as completed.');
    }

    public function delete(int $id)
    {
        $this->scheduleTable->where('id', $id)->update(['status' => 'cancelled', 'updated_at' => date('Y-m-d H:i:s')]);
        return redirect()->to('/manager/schedule')->with('success', 'Task cancelled.');
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// REPORTS
// ══════════════════════════════════════════════════════════════════════════════

class ReportController extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        return $this->dashboardView('manager/reports', [
            'pageTitle' => 'Reports',
            'stats' => [
                'births_this_month' => $db->table('goats')
                    ->where("DATE_FORMAT(created_at, '%Y-%m')", date('Y-m'))
                    ->where('status', 'active')
                    ->countAllResults(),
                'mortality_this_month' => $db->table('goats')
                    ->where("DATE_FORMAT(updated_at, '%Y-%m')", date('Y-m'))
                    ->where('status', 'deceased')
                    ->countAllResults(),
            ],
        ]);
    }

    public function herd(): string
    {
        $goatModel = new GoatModel();
        return $this->dashboardView('manager/report_herd', [
            'pageTitle' => 'Herd Report',
            'herd'      => $goatModel->getFullHerd(),
        ]);
    }

    public function health(): string
    {
        $visitModel = new VetVisitModel();
        return $this->dashboardView('manager/report_health', [
            'pageTitle' => 'Health Report',
            'flags'     => $visitModel->getActiveFlags(),
        ]);
    }

    public function members(): string
    {
        $userModel = new UserModel();
        return $this->dashboardView('manager/report_members', [
            'pageTitle' => 'Members Report',
            'members'   => $userModel->getByRole('member'),
        ]);
    }

    public function export(string $type)
    {
        // TODO: implement CSV/PDF export using PhpSpreadsheet / DomPDF
        return redirect()->to('/manager/reports')->with('info', 'Export for "' . $type . '" coming soon.');
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// MEMBER VIEW (read-only for manager)
// ══════════════════════════════════════════════════════════════════════════════

class MemberController extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        $members = $db->table('users u')
                      ->select('u.*, COUNT(g.id) as goat_count')
                      ->join('goats g', 'g.member_id = u.id AND g.status = "active"', 'left')
                      ->where('u.role', 'member')
                      ->where('u.deleted_at', null)
                      ->groupBy('u.id')
                      ->orderBy('u.created_at', 'DESC')
                      ->get()->getResultArray();

        return $this->dashboardView('manager/members', [
            'pageTitle' => 'Goat Banking Members',
            'members'   => $members,
        ]);
    }

    public function show(int $id): string
    {
        $userModel = new UserModel();
        $goatModel = new GoatModel();
        $user      = $userModel->find($id);

        if (! $user || $user['role'] !== 'member') {
            return redirect()->to('/manager/members')->with('error', 'Member not found.');
        }

        return $this->dashboardView('manager/member_detail', [
            'pageTitle' => $user['first_name'] . ' ' . $user['last_name'] . ' — Member Profile',
            'member'    => $user,
            'goats'     => $goatModel->getByMember($id),
        ]);
    }
}
