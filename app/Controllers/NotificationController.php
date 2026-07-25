<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    public function markRead(int $id)
    {
        $notifs = new NotificationModel();
        $notif  = $notifs->find($id);
        if ($notif && (int) $notif['user_id'] === $this->currentUserId()) {
            $notifs->markRead($id);
        }
        if (! empty($notif['link'])) {
            return redirect()->to($notif['link']);
        }
        return redirect()->back();
    }

    public function markAllRead()
    {
        (new NotificationModel())->markAllRead($this->currentUserId());
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
