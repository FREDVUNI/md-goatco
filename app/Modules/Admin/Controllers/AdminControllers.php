<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\MemberApplicationModel;
use App\Models\GoatModel;
use App\Models\NotificationModel;
use App\Libraries\Mailer;

// ══════════════════════════════════════════════════════════════════════════════
// ADMIN DASHBOARD
// ══════════════════════════════════════════════════════════════════════════════

class DashboardController extends BaseController
{
    public function index(): string
    {
        $users        = new UserModel();
        $applications = new MemberApplicationModel();
        $goats        = new GoatModel();
        $notifications = new NotificationModel();

        $data = [
            'pageTitle'       => 'Dashboard Overview',
            'totalMembers'    => count($users->getByRole('member')),
            'pendingCount'    => $applications->countPending(),
            'goatStats'       => $goats->getStats(),
            'recentPending'   => $applications->getPending(),
            'recentMembers'   => array_slice($users->getByRole('member'), 0, 5),
            'staffCounts'     => $users->countByRole(),
            'notifications'   => $notifications->getForUser($this->currentUserId(), 10),
            'unreadCount'     => $notifications->getUnreadCount($this->currentUserId()),
        ];

        return $this->dashboardView('admin/dashboard', $data);
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// APPLICATION REVIEW
// ══════════════════════════════════════════════════════════════════════════════

class ApplicationController extends BaseController
{
    private MemberApplicationModel $applications;
    private UserModel              $users;

    public function __construct()
    {
        $this->applications = new MemberApplicationModel();
        $this->users        = new UserModel();
    }

    public function index(): string
    {
        return $this->dashboardView('admin/applications', [
            'pageTitle'    => 'Goat Banking Applications',
            'applications' => $this->applications->getPending(),
        ]);
    }

    public function show(int $id): string
    {
        $application = $this->applications->getWithUser($id);

        if (! $application) {
            return redirect()->to('/admin/applications')->with('error', 'Application not found.');
        }

        return $this->dashboardView('admin/application_detail', [
            'pageTitle'   => 'Review Application — ' . $application['first_name'] . ' ' . $application['last_name'],
            'application' => $application,
        ]);
    }

    /**
     * Approve an application:
     * 1. Mark application approved
     * 2. Activate the user account (status = active)
     * 3. Notify the member
     */
    public function approve(int $id)
    {
        $application = $this->applications->find($id);

        if (! $application) {
            return redirect()->to('/admin/applications')->with('error', 'Application not found.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Approve the application
            $this->applications->approve($id, $this->currentUserId());

            // Activate the user account
            $this->users->activate($application['user_id']);

            // Notify the member
            $notifModel = new NotificationModel();
            $notifModel->notifyUser(
                $application['user_id'],
                'Your Goat Banking application has been approved! 🎉',
                'Welcome to MD Goatco Farm Goat Banking. Log in to view your dashboard.',
                'success',
                '/member/dashboard'
            );

            $db->transCommit();

            // Email the applicant — failures are logged, never block the approval
            $mailer = new Mailer();
            $mailer->send(
                $this->users->find($application['user_id'])['email'] ?? '',
                'Your Goat Banking application is approved! — MD Goatco Farm',
                'application_approved',
                ['firstName' => $application['first_name']]
            );

            return redirect()->to('/admin/applications')
                             ->with('success', $application['first_name'] . ' ' . $application['last_name'] . '\'s application has been approved and their account activated.');

        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Application approval failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Could not approve application. Please try again.');
        }
    }

    /**
     * Reject an application:
     * 1. Mark application rejected
     * 2. Set user status to rejected
     * 3. Notify the member
     */
    public function reject(int $id)
    {
        $application = $this->applications->find($id);
        $reason      = $this->request->getPost('reason') ?? '';

        if (! $application) {
            return redirect()->to('/admin/applications')->with('error', 'Application not found.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $this->applications->reject($id, $this->currentUserId(), $reason);
            $this->users->update($application['user_id'], ['status' => 'rejected']);

            $notifModel = new NotificationModel();
            $notifModel->notifyUser(
                $application['user_id'],
                'Update on your Goat Banking application',
                'Unfortunately we were unable to approve your application at this time. Please contact hello@mdgoatco.farm for more information.',
                'warning'
            );

            $db->transCommit();

            $mailer = new Mailer();
            $mailer->send(
                $this->users->find($application['user_id'])['email'] ?? '',
                'Update on your Goat Banking application — MD Goatco Farm',
                'application_rejected',
                ['firstName' => $application['first_name'], 'reason' => $reason]
            );

            return redirect()->to('/admin/applications')
                             ->with('success', 'Application rejected and applicant notified.');

        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Could not reject application. Please try again.');
        }
    }

    /**
     * Request additional information from the applicant.
     */
    public function requestInfo(int $id)
    {
        $note = $this->request->getPost('note') ?? '';

        if (empty(trim($note))) {
            return redirect()->back()->with('error', 'Please provide a note explaining what information is needed.');
        }

        $application = $this->applications->find($id);

        if (! $application) {
            return redirect()->to('/admin/applications')->with('error', 'Application not found.');
        }

        $this->applications->requestInfo($id, $this->currentUserId(), $note);

        $notifModel = new NotificationModel();
        $notifModel->notifyUser(
            $application['user_id'],
            'Additional information required for your application',
            $note,
            'info'
        );

        $mailer = new Mailer();
        $mailer->send(
            $this->users->find($application['user_id'])['email'] ?? '',
            'We need a bit more information — MD Goatco Farm',
            'application_info_requested',
            ['firstName' => $application['first_name'], 'note' => $note]
        );

        return redirect()->to('/admin/applications/' . $id)
                         ->with('success', 'Information request sent to applicant.');
    }
}
