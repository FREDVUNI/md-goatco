<?php

declare(strict_types=1);

namespace App\Modules\Member\Controllers;

use App\Controllers\BaseController;
use App\Models\GoatModel;
use App\Models\VetVisitModel;
use App\Models\WeightLogModel;
use App\Models\TransactionModel;
use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Models\MemberApplicationModel;
use App\Libraries\Mailer;

// ══════════════════════════════════════════════════════════════════════════════
// MEMBER DASHBOARD
// ══════════════════════════════════════════════════════════════════════════════

class DashboardController extends BaseController
{
    public function index(): string
    {
        $memberId     = $this->currentUserId();
        $goats        = new GoatModel();
        $transactions = new TransactionModel();
        $notifs       = new NotificationModel();
        $visits       = new VetVisitModel();

        $myGoats    = $goats->getWithLatestWeight($memberId);
        $healthyCount = count(array_filter($myGoats, fn($g) => empty($g['is_flagged'])));

        return $this->dashboardView('member/dashboard', [
            'pageTitle'     => 'My Dashboard',
            'goats'         => $myGoats,
            'goatCount'     => count($myGoats),
            'healthyCount'  => $healthyCount,
            'balance'       => $transactions->getCurrentBalance($memberId),
            'totalCredited' => $transactions->getTotalCredited($memberId),
            'recentTxns'    => array_slice($transactions->getByMember($memberId), 0, 5),
            'notifications' => $notifs->getForUser($memberId, 10),
            'unreadCount'   => $notifs->getUnreadCount($memberId),
        ]);
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// MEMBER PORTFOLIO — view individual goats
// ══════════════════════════════════════════════════════════════════════════════

class PortfolioController extends BaseController
{
    private GoatModel     $goats;
    private VetVisitModel $visits;
    private WeightLogModel $weights;

    public function __construct()
    {
        $this->goats   = new GoatModel();
        $this->visits  = new VetVisitModel();
        $this->weights = new WeightLogModel();
    }

    /**
     * List all goats in member's portfolio.
     */
    public function index(): string
    {
        return $this->dashboardView('member/portfolio', [
            'pageTitle' => 'My Goats',
            'goats'     => $this->goats->getWithLatestWeight($this->currentUserId()),
        ]);
    }

    /**
     * Individual goat profile — health history + weight chart.
     * Gate: goat must belong to the logged-in member.
     */
    public function show(int $goatId): string
    {
        $goat = $this->goats->find($goatId);

        // Ownership check — members can only see their own goats
        if (! $goat || (int) $goat['member_id'] !== $this->currentUserId()) {
            return redirect()->to('/member/goats')->with('error', 'Goat not found in your portfolio.');
        }

        return $this->dashboardView('member/goat_profile', [
            'pageTitle'   => $goat['name'] . ' — Goat Profile',
            'goat'        => $goat,
            'healthHistory' => $this->visits->getByGoat($goatId),
            'weightHistory' => $this->weights->getByGoat($goatId),
            'growthChart'   => $this->weights->getGrowthChart($goatId),
            'latestWeight'  => $this->weights->getLatestForGoat($goatId),
        ]);
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// MEMBER STATEMENTS
// ══════════════════════════════════════════════════════════════════════════════

class StatementController extends BaseController
{
    public function index(): string
    {
        $memberId     = $this->currentUserId();
        $transactions = new TransactionModel();

        return $this->dashboardView('member/statements', [
            'pageTitle'     => 'My Statements',
            'transactions'  => $transactions->getByMember($memberId),
            'balance'       => $transactions->getCurrentBalance($memberId),
            'totalCredited' => $transactions->getTotalCredited($memberId),
            'totalDebited'  => $transactions->getTotalDebited($memberId),
        ]);
    }

    /**
     * Generate a PDF statement for download.
     */
    public function download()
    {
        $memberId     = $this->currentUserId();
        $user         = $this->currentUser();
        $transactions = new TransactionModel();

        $html = view('member/statement_pdf', [
            'user'          => $user,
            'transactions'  => $transactions->getByMember($memberId, 500),
            'balance'       => $transactions->getCurrentBalance($memberId),
            'totalCredited' => $transactions->getTotalCredited($memberId),
            'totalDebited'  => $transactions->getTotalDebited($memberId),
            'generatedAt'   => date('j M Y, g:i A'),
        ]);

        try {
            $dompdf = new \Dompdf\Dompdf([
                'isRemoteEnabled' => false,
                'defaultFont'     => 'Helvetica',
            ]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = 'mdgoatco-statement-' . date('Y-m-d') . '.pdf';

            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody($dompdf->output());

        } catch (\Throwable $e) {
            log_message('error', 'Statement PDF generation failed for member ' . $memberId . ': ' . $e->getMessage());

            return redirect()->back()->with('error', 'Could not generate your statement PDF. Please try again.');
        }
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// MEMBER ACCOUNT
// ══════════════════════════════════════════════════════════════════════════════

class AccountController extends BaseController
{
    public function index(): string
    {
        $users        = new UserModel();
        $applications = new MemberApplicationModel();

        $user        = $users->find($this->currentUserId());
        $application = $applications->findByUserId($this->currentUserId());

        return $this->dashboardView('member/account', [
            'pageTitle'   => 'My Account',
            'user'        => $user,
            'application' => $application,
        ]);
    }

    public function update()
    {
        $rules = [
            'phone'   => 'required|min_length[10]|max_length[20]',
            'address' => 'required|min_length[5]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $users = new UserModel();
        $users->update($this->currentUserId(), [
            'phone' => $this->request->getPost('phone'),
        ]);

        // Update application address
        $applications = new MemberApplicationModel();
        $app = $applications->findByUserId($this->currentUserId());
        if ($app) {
            $applications->update($app['id'], ['address' => $this->request->getPost('address')]);
        }

        return redirect()->to('/member/account')->with('success', 'Account updated.');
    }

    public function changePassword()
    {
        $rules = [
            'current_password'  => 'required',
            'new_password'      => 'required|min_length[8]',
            'confirm_password'  => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $users = new UserModel();
        $user  = $users->find($this->currentUserId());

        if (! $users->verifyPassword($this->request->getPost('current_password'), $user['password_hash'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $users->update($this->currentUserId(), [
            'password' => $this->request->getPost('new_password'),
        ]);

        return redirect()->to('/member/account')->with('success', 'Password changed successfully.');
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// MEMBER SUPPORT
// ══════════════════════════════════════════════════════════════════════════════

class SupportController extends BaseController
{
    public function index(): string
    {
        return $this->dashboardView('member/support', ['pageTitle' => 'Support']);
    }

    public function send()
    {
        $rules = [
            'subject' => 'required',
            'message' => 'required|min_length[20]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Notify admins of support request
        $notifModel = new NotificationModel();
        $user       = $this->currentUser();

        $notifModel->notifyAllAdmins(
            'Support request from ' . $user['first_name'] . ' ' . $user['last_name'],
            '[' . $this->request->getPost('subject') . '] ' . $this->request->getPost('message'),
            'info'
        );

        $mailer = new Mailer();
        $mailer->sendToAdmins(
            '[Support] ' . $this->request->getPost('subject'),
            'support_request_admin',
            [
                'memberName'  => $user['first_name'] . ' ' . $user['last_name'],
                'memberEmail' => $user['email'],
                'subject'     => $this->request->getPost('subject'),
                'message'     => $this->request->getPost('message'),
            ]
        );

        return redirect()->to('/member/support')
                         ->with('success', 'Your message has been sent. We will reply within 1–2 working days.');
    }
}
