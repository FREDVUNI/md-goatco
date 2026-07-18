<?php
declare(strict_types=1);
namespace App\Modules\Manager\Controllers;
use App\Controllers\BaseController;
use App\Models\ContactMessageModel;

class ContactController extends BaseController
{
    private ContactMessageModel $messages;
    public function __construct() { $this->messages = new ContactMessageModel(); }

    public function index(): string
    {
        $search = $this->searchTerm();
        [$messages, $pager] = $this->paginateBuilder($this->messages->getListQuery($search));

        return $this->dashboardView('manager/contact', [
            'pageTitle'       => 'Contact Messages',
            'messages'        => $messages,
            'pager'           => $pager,
            'search'          => $search,
            'contactNewCount' => $this->messages->countNew(),
        ]);
    }

    public function respond(int $id)
    {
        $message = $this->messages->find($id);
        if (! $message) return redirect()->to('/manager/contact')->with('error', 'Message not found.');
        $this->messages->markResponded($id, $this->currentUserId());
        return redirect()->to('/manager/contact')->with('success', 'Marked as responded.');
    }
}
