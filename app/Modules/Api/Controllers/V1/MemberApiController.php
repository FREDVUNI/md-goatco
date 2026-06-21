<?php

declare(strict_types=1);

namespace App\Modules\Api\Controllers\V1;

use App\Models\GoatModel;
use App\Models\VetVisitModel;
use App\Models\WeightLogModel;
use App\Models\TransactionModel;
use App\Models\NotificationModel;

class MemberApiController extends BaseApiController
{
    public function dashboard(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireRole('member')) {
            return $this->forbidden('Member access only.');
        }

        $memberId     = $this->authUserId();
        $goats        = new GoatModel();
        $transactions = new TransactionModel();
        $notifs       = new NotificationModel();

        $myGoats = $goats->getWithLatestWeight($memberId);

        return $this->ok([
            'goat_count'    => count($myGoats),
            'healthy_count' => count(array_filter($myGoats, fn($g) => empty($g['is_flagged']))),
            'balance'       => $transactions->getCurrentBalance($memberId),
            'total_credited'=> $transactions->getTotalCredited($memberId),
            'unread_notifs' => $notifs->getUnreadCount($memberId),
        ]);
    }

    public function goats(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireRole('member')) {
            return $this->forbidden();
        }

        $goats   = new GoatModel();
        $results = $goats->getWithLatestWeight($this->authUserId());

        return $this->ok($results);
    }

    public function goat(int $goatId): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireRole('member')) {
            return $this->forbidden();
        }

        $goatModel = new GoatModel();
        $goat      = $goatModel->find($goatId);

        if (! $goat || (int) $goat['member_id'] !== $this->authUserId()) {
            return $this->notFound('Goat not found in your portfolio.');
        }

        $visits  = new VetVisitModel();
        $weights = new WeightLogModel();

        return $this->ok([
            'goat'          => $goat,
            'health_history'=> $visits->getByGoat($goatId),
            'weight_history'=> $weights->getByGoat($goatId),
            'growth_chart'  => $weights->getGrowthChart($goatId),
        ]);
    }

    public function statements(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireRole('member')) {
            return $this->forbidden();
        }

        $memberId     = $this->authUserId();
        $transactions = new TransactionModel();

        return $this->ok([
            'balance'      => $transactions->getCurrentBalance($memberId),
            'total_credited'=> $transactions->getTotalCredited($memberId),
            'total_debited' => $transactions->getTotalDebited($memberId),
            'transactions' => $transactions->getByMember($memberId),
        ]);
    }

    public function notifications(): \CodeIgniter\HTTP\ResponseInterface
    {
        $notifs = new NotificationModel();

        return $this->ok([
            'unread_count'  => $notifs->getUnreadCount($this->authUserId()),
            'notifications' => $notifs->getForUser($this->authUserId()),
        ]);
    }

    public function support(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! $this->requireRole('member')) {
            return $this->forbidden();
        }

        $body    = $this->request->getJSON(true) ?? [];
        $subject = $body['subject'] ?? '';
        $message = $body['message'] ?? '';

        if (empty($subject) || empty($message)) {
            return $this->error('Subject and message are required.');
        }

        $notifModel = new NotificationModel();
        $user       = $this->authUser();

        $notifModel->notifyAllAdmins(
            'Support request from ' . ($user->name ?? 'Member'),
            '[' . $subject . '] ' . $message,
            'info'
        );

        return $this->ok(null, 'Support request sent. We will reply within 1–2 working days.');
    }
}
