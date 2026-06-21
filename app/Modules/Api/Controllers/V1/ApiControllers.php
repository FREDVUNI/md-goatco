<?php

declare(strict_types=1);

namespace App\Modules\Api\Controllers\V1;

use App\Models\GoatModel;
use App\Models\VetVisitModel;
use App\Models\UserModel;
use App\Models\NotificationModel;

// ══════════════════════════════════════════════════════════════════════════════
// VET API
// ══════════════════════════════════════════════════════════════════════════════

class VetApiController extends BaseApiController
{
    private function requireVet(): bool
    {
        return $this->requireRole('vet', 'manager', 'super_admin');
    }

    public function tasks(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireVet()) return $this->forbidden();

        $db    = \Config\Database::connect();
        $tasks = $db->table('vet_schedules')
                    ->where('DATE(scheduled_at)', date('Y-m-d'))
                    ->where('status !=', 'cancelled')
                    ->orderBy('scheduled_at', 'ASC')
                    ->get()->getResultArray();

        return $this->ok($tasks);
    }

    public function completeTask(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireVet()) return $this->forbidden();

        $db = \Config\Database::connect();
        $db->table('vet_schedules')->where('id', $id)->update([
            'status'       => 'completed',
            'completed_at' => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        return $this->ok(null, 'Task marked as completed.');
    }

    public function visits(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireVet()) return $this->forbidden();

        $visitModel = new VetVisitModel();
        return $this->ok($visitModel->getByVet($this->authUserId()));
    }

    public function logVisit(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireVet()) return $this->forbidden();

        $body = $this->request->getJSON(true) ?? [];

        if (empty($body['goat_id']) || empty($body['visit_type']) || empty($body['clinical_notes'])) {
            return $this->error('goat_id, visit_type and clinical_notes are required.');
        }

        $visitModel = new VetVisitModel();
        $visitId    = $visitModel->insert([
            'goat_id'        => (int) $body['goat_id'],
            'vet_id'         => $this->authUserId(),
            'visit_type'     => $body['visit_type'],
            'visit_date'     => $body['visit_date'] ?? date('Y-m-d H:i:s'),
            'temperature'    => $body['temperature'] ?? null,
            'weight_kg'      => $body['weight_kg'] ?? null,
            'medication'     => $body['medication'] ?? null,
            'clinical_notes' => $body['clinical_notes'],
            'is_flagged'     => ! empty($body['is_flagged']) ? 1 : 0,
            'flag_reason'    => $body['flag_reason'] ?? null,
            'followup_date'  => $body['followup_date'] ?? null,
        ]);

        if (! empty($body['is_flagged'])) {
            $goatModel  = new GoatModel();
            $goat       = $goatModel->find($body['goat_id']);
            $notifModel = new NotificationModel();
            $notifModel->notifyAllAdmins(
                'Health flag: ' . ($goat['name'] ?? '') . ' (' . ($goat['tag_number'] ?? '') . ')',
                $body['flag_reason'] ?? '',
                'alert'
            );
            if (! empty($goat['member_id'])) {
                $notifModel->notifyUser(
                    $goat['member_id'],
                    'Health update: ' . ($goat['name'] ?? ''),
                    'Our vet has noted a health concern. The team is monitoring closely.',
                    'warning',
                    '/member/goats/' . $goat['id']
                );
            }
        }

        return $this->created(['visit_id' => $visitId], 'Visit recorded successfully.');
    }

    public function flags(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireVet()) return $this->forbidden();

        $visitModel = new VetVisitModel();
        return $this->ok($visitModel->getMyActiveFlags($this->authUserId()));
    }

    public function resolveFlag(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireVet()) return $this->forbidden();

        $visitModel = new VetVisitModel();
        $visitModel->resolveFlag($id);
        return $this->ok(null, 'Flag resolved.');
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// MANAGER API
// ══════════════════════════════════════════════════════════════════════════════

class ManagerApiController extends BaseApiController
{
    private function requireManager(): bool
    {
        return $this->requireRole('manager', 'super_admin');
    }

    public function herd(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireManager()) return $this->forbidden();

        $goatModel = new GoatModel();
        return $this->ok($goatModel->getFullHerd());
    }

    public function goat(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireManager()) return $this->forbidden();

        $goatModel  = new GoatModel();
        $visitModel = new VetVisitModel();
        $goat       = $goatModel->find($id);

        if (! $goat) return $this->notFound('Goat not found.');

        return $this->ok([
            'goat'   => $goat,
            'visits' => $visitModel->getByGoat($id),
        ]);
    }

    public function healthFlags(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireManager()) return $this->forbidden();

        $visitModel = new VetVisitModel();
        return $this->ok($visitModel->getActiveFlags());
    }

    public function schedule(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireManager()) return $this->forbidden();

        $db = \Config\Database::connect();
        $tasks = $db->table('vet_schedules vs')
                    ->select('vs.*, u.first_name, u.last_name')
                    ->join('users u', 'u.id = vs.assigned_vet_id', 'left')
                    ->where('vs.status !=', 'cancelled')
                    ->orderBy('vs.scheduled_at', 'ASC')
                    ->get()->getResultArray();

        return $this->ok($tasks);
    }

    public function report(string $type): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireManager()) return $this->forbidden();

        switch ($type) {
            case 'herd':
                $goatModel = new GoatModel();
                return $this->ok($goatModel->getFullHerd());

            case 'health':
                $visitModel = new VetVisitModel();
                return $this->ok($visitModel->getActiveFlags());

            case 'members':
                $userModel = new UserModel();
                return $this->ok($userModel->getByRole('member'));

            default:
                return $this->error('Unknown report type: ' . $type);
        }
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// ADMIN API
// ══════════════════════════════════════════════════════════════════════════════

class AdminApiController extends BaseApiController
{
    private function requireAdmin(): bool
    {
        return $this->requireRole('super_admin');
    }

    public function applications(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireAdmin()) return $this->forbidden();

        $appModel = new \App\Models\MemberApplicationModel();
        return $this->ok($appModel->getPending());
    }

    public function approve(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireAdmin()) return $this->forbidden();

        $appModel  = new \App\Models\MemberApplicationModel();
        $userModel = new UserModel();
        $app       = $appModel->find($id);

        if (! $app) return $this->notFound('Application not found.');

        $appModel->approve($id, $this->authUserId());
        $userModel->activate($app['user_id']);

        $notifModel = new NotificationModel();
        $notifModel->notifyUser(
            $app['user_id'],
            'Your Goat Banking application has been approved! 🎉',
            'Welcome to MD Goatco Farm Goat Banking.',
            'success'
        );

        return $this->ok(null, 'Application approved and account activated.');
    }

    public function reject(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireAdmin()) return $this->forbidden();

        $body     = $this->request->getJSON(true) ?? [];
        $reason   = $body['reason'] ?? '';
        $appModel = new \App\Models\MemberApplicationModel();
        $app      = $appModel->find($id);

        if (! $app) return $this->notFound('Application not found.');

        $appModel->reject($id, $this->authUserId(), $reason);

        $userModel = new UserModel();
        $userModel->update($app['user_id'], ['status' => 'rejected']);

        return $this->ok(null, 'Application rejected.');
    }

    public function members(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireAdmin()) return $this->forbidden();

        $userModel = new UserModel();
        return $this->ok($userModel->getByRole('member'));
    }

    public function staff(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireAdmin()) return $this->forbidden();

        $userModel = new UserModel();
        return $this->ok($userModel->getStaff());
    }

    public function createStaff(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireAdmin()) return $this->forbidden();

        $body = $this->request->getJSON(true) ?? [];
        $required = ['first_name', 'last_name', 'email', 'role', 'password'];

        foreach ($required as $field) {
            if (empty($body[$field])) {
                return $this->error($field . ' is required.');
            }
        }

        if (! in_array($body['role'], ['manager', 'vet'], true)) {
            return $this->error('Role must be manager or vet.');
        }

        $userModel = new UserModel();
        $userId    = $userModel->insert([
            'first_name'  => $body['first_name'],
            'last_name'   => $body['last_name'],
            'email'       => $body['email'],
            'phone'       => $body['phone'] ?? null,
            'role'        => $body['role'],
            'password'    => $body['password'],
            'status'      => 'active',
            'created_by'  => $this->authUserId(),
        ]);

        if (! $userId) {
            return $this->error('Could not create staff account. Email may already be registered.');
        }

        return $this->created(['user_id' => $userId], 'Staff account created.');
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// NOTIFICATIONS API (all roles)
// ══════════════════════════════════════════════════════════════════════════════

class NotificationApiController extends BaseApiController
{
    public function index(): \CodeIgniter\HTTP\ResponseInterface
    {
        $notifModel = new NotificationModel();
        return $this->ok([
            'unread_count'  => $notifModel->getUnreadCount($this->authUserId()),
            'notifications' => $notifModel->getForUser($this->authUserId()),
        ]);
    }

    public function markRead(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        $notifModel = new NotificationModel();
        $notifModel->markRead($id, $this->authUserId());
        return $this->ok(null, 'Notification marked as read.');
    }

    public function markAllRead(): \CodeIgniter\HTTP\ResponseInterface
    {
        $notifModel = new NotificationModel();
        $notifModel->markAllRead($this->authUserId());
        return $this->ok(null, 'All notifications marked as read.');
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// GOAT API (shared — role filtered inside controller)
// ══════════════════════════════════════════════════════════════════════════════

class GoatApiController extends BaseApiController
{
    public function index(): \CodeIgniter\HTTP\ResponseInterface
    {
        $role      = $this->authUserRole();
        $goatModel = new GoatModel();

        // Members only see their own goats
        if ($role === 'member') {
            return $this->ok($goatModel->getWithLatestWeight($this->authUserId()));
        }

        // Tag search for vet lookup
        $tag = $this->request->getGet('tag');
        if ($tag) {
            $goat = $goatModel->findByTag($tag);
            return $this->ok($goat ? [$goat] : []);
        }

        // Staff see full herd
        return $this->ok($goatModel->getFullHerd());
    }

    public function show(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        $goatModel = new GoatModel();
        $goat      = $goatModel->find($id);

        if (! $goat) return $this->notFound();

        // Members can only see their own goats
        if ($this->authUserRole() === 'member' && (int) $goat['member_id'] !== $this->authUserId()) {
            return $this->forbidden('This goat is not in your portfolio.');
        }

        return $this->ok($goat);
    }

    public function healthHistory(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        $goatModel  = new GoatModel();
        $visitModel = new VetVisitModel();
        $goat       = $goatModel->find($id);

        if (! $goat) return $this->notFound();

        if ($this->authUserRole() === 'member' && (int) $goat['member_id'] !== $this->authUserId()) {
            return $this->forbidden();
        }

        return $this->ok($visitModel->getByGoat($id));
    }

    public function weightHistory(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        $goatModel   = new GoatModel();
        $weightModel = new \App\Models\WeightLogModel();
        $goat        = $goatModel->find($id);

        if (! $goat) return $this->notFound();

        if ($this->authUserRole() === 'member' && (int) $goat['member_id'] !== $this->authUserId()) {
            return $this->forbidden();
        }

        return $this->ok([
            'weights'      => $weightModel->getByGoat($id),
            'growth_chart' => $weightModel->getGrowthChart($id),
        ]);
    }

    public function logWeight(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireRole('vet', 'manager', 'super_admin')) return $this->forbidden();

        $body      = $this->request->getJSON(true) ?? [];
        $weightKg  = $body['weight_kg'] ?? null;

        if (! $weightKg) return $this->error('weight_kg is required.');

        $weightModel = new \App\Models\WeightLogModel();
        $weightModel->insert([
            'goat_id'   => $id,
            'weight_kg' => (float) $weightKg,
            'logged_by' => $this->authUserId(),
            'logged_at' => $body['logged_at'] ?? date('Y-m-d H:i:s'),
        ]);

        return $this->created(null, 'Weight logged.');
    }
}
