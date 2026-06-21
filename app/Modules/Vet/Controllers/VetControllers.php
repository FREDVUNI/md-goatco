<?php

declare(strict_types=1);

namespace App\Modules\Vet\Controllers;

use App\Controllers\BaseController;
use App\Models\GoatModel;
use App\Models\VetVisitModel;
use App\Models\WeightLogModel;
use App\Models\NotificationModel;

// ══════════════════════════════════════════════════════════════════════════════
// VET DASHBOARD
// ══════════════════════════════════════════════════════════════════════════════

class DashboardController extends BaseController
{
    public function index(): string
    {
        $vetVisits = new VetVisitModel();
        $goats     = new GoatModel();
        $notifs    = new NotificationModel();
        $vetId     = $this->currentUserId();

        return $this->dashboardView('vet/dashboard', [
            'pageTitle'   => 'My Dashboard',
            'myFlags'     => $vetVisits->getMyActiveFlags($vetId),
            'recentVisits'=> array_slice($vetVisits->getByVet($vetId), 0, 10),
            'flagCount'   => count($vetVisits->getMyActiveFlags($vetId)),
            'unreadCount' => $notifs->getUnreadCount($vetId),
        ]);
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// VET VISIT LOGGING
// ══════════════════════════════════════════════════════════════════════════════

class VisitController extends BaseController
{
    private VetVisitModel  $visits;
    private GoatModel      $goats;

    public function __construct()
    {
        $this->visits = new VetVisitModel();
        $this->goats  = new GoatModel();
    }

    public function create(): string
    {
        // Allow pre-filling tag from query string e.g. /vet/visits/log?tag=PGF-1042
        $tag  = $this->request->getGet('tag');
        $goat = $tag ? $this->goats->findByTag($tag) : null;

        return $this->dashboardView('vet/visit_log', [
            'pageTitle'    => 'Log a Visit',
            'prefillGoat'  => $goat,
            'visitTypes'   => [
                'routine_checkup', 'vaccination', 'weight_check',
                'treatment', 'follow_up', 'emergency', 'deworming',
            ],
        ]);
    }

    public function store()
    {
        $rules = [
            'goat_id'        => 'required|integer',
            'visit_type'     => 'required',
            'visit_date'     => 'required|valid_date[Y-m-d\TH:i]',
            'clinical_notes' => 'required|min_length[10]',
            'weight_kg'      => 'permit_empty|decimal',
            'temperature'    => 'permit_empty|decimal',
            'followup_date'  => 'permit_empty|valid_date[Y-m-d]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $isFlagged = (bool) $this->request->getPost('is_flagged');

        $visitId = $this->visits->insert([
            'goat_id'        => $this->request->getPost('goat_id'),
            'vet_id'         => $this->currentUserId(),
            'visit_type'     => $this->request->getPost('visit_type'),
            'visit_date'     => $this->request->getPost('visit_date'),
            'temperature'    => $this->request->getPost('temperature') ?: null,
            'weight_kg'      => $this->request->getPost('weight_kg') ?: null,
            'medication'     => $this->request->getPost('medication'),
            'clinical_notes' => $this->request->getPost('clinical_notes'),
            'is_flagged'     => $isFlagged ? 1 : 0,
            'flag_reason'    => $isFlagged ? $this->request->getPost('flag_reason') : null,
            'followup_date'  => $this->request->getPost('followup_date') ?: null,
        ]);

        // If flagged, notify admins and managers
        if ($isFlagged && $visitId) {
            $goat       = $this->goats->find($this->request->getPost('goat_id'));
            $notifModel = new NotificationModel();

            $notifModel->notifyAllAdmins(
                'Health flag raised: ' . ($goat['name'] ?? 'Unknown') . ' (' . ($goat['tag_number'] ?? '') . ')',
                $this->request->getPost('flag_reason'),
                'alert'
            );

            // Also notify the member who owns this goat
            if (! empty($goat['member_id'])) {
                $notifModel->notifyUser(
                    $goat['member_id'],
                    'Health update: ' . $goat['name'],
                    'Our vet has raised a health concern for ' . $goat['name'] . '. The team is monitoring closely.',
                    'warning',
                    '/member/goats/' . $goat['id']
                );
            }
        }

        return redirect()->to('/vet/visits/history')
                         ->with('success', 'Visit record saved' . ($isFlagged ? ' and health flag raised.' : '.'));
    }

    public function show(int $id): string
    {
        $visit = $this->visits->find($id);

        if (! $visit) {
            return redirect()->to('/vet/dashboard')->with('error', 'Visit not found.');
        }

        return $this->dashboardView('vet/visit_detail', [
            'pageTitle' => 'Visit Record',
            'visit'     => $visit,
            'goat'      => $this->goats->find($visit['goat_id']),
        ]);
    }

    public function history(): string
    {
        return $this->dashboardView('vet/visit_history', [
            'pageTitle' => 'Visit History',
            'visits'    => $this->visits->getByVet($this->currentUserId()),
        ]);
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// VET FLAG MANAGEMENT
// ══════════════════════════════════════════════════════════════════════════════

class FlagController extends BaseController
{
    public function index(): string
    {
        $visits = new VetVisitModel();

        return $this->dashboardView('vet/flags', [
            'pageTitle' => 'My Active Flags',
            'flags'     => $visits->getMyActiveFlags($this->currentUserId()),
        ]);
    }

    public function resolve(int $visitId)
    {
        $visits = new VetVisitModel();
        $visit  = $visits->find($visitId);

        if (! $visit || $visit['vet_id'] !== $this->currentUserId()) {
            return redirect()->to('/vet/flags')->with('error', 'Flag not found or not yours to resolve.');
        }

        $visits->resolveFlag($visitId);

        // Notify member that flag is resolved
        $goat = (new GoatModel())->find($visit['goat_id']);
        if ($goat && $goat['member_id']) {
            $notifModel = new NotificationModel();
            $notifModel->notifyUser(
                $goat['member_id'],
                'Health concern resolved: ' . $goat['name'],
                $goat['name'] . ' has been cleared by the vet. Everything is looking good.',
                'success',
                '/member/goats/' . $goat['id']
            );
        }

        return redirect()->to('/vet/flags')->with('success', 'Flag marked as resolved.');
    }
}
