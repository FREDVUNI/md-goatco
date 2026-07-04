<?php
declare(strict_types=1);
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;
use App\Models\GoatModel;
use App\Models\UserModel;

class HerdController extends BaseController
{
    private GoatModel $goats;
    public function __construct() { $this->goats = new GoatModel(); }

    public function index(): string
    {
        return $this->dashboardView('admin/herd', [
            'pageTitle' => 'Herd Overview',
            'herd'      => $this->goats->getFullHerd(),
            'stats'     => $this->goats->getStats(),
            'pendingCount' => (new \App\Models\MemberApplicationModel())->countPending(),
        ]);
    }

    public function show(int $id) { return redirect()->to('/admin/herd'); }

    public function create(): string
    {
        return $this->dashboardView('admin/herd_form', [
            'pageTitle' => 'Add Animal',
            'members'   => (new UserModel())->getByRole('member'),
        ]);
    }

    public function store()
    {
        if (! $this->validate(['tag_number'=>'required|is_unique[goats.tag_number]','name'=>'required','sex'=>'required|in_list[male,female]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->goats->insert([
            'tag_number' => strtoupper(trim($this->request->getPost('tag_number'))),
            'name'       => $this->request->getPost('name'),
            'breed'      => $this->request->getPost('breed'),
            'sex'        => $this->request->getPost('sex'),
            'dob'        => $this->request->getPost('dob') ?: null,
            'pen_id'     => $this->request->getPost('pen_id'),
            'member_id'  => $this->request->getPost('member_id') ?: null,
            'status'     => 'active',
            'notes'      => $this->request->getPost('notes'),
        ]);
        return redirect()->to('/admin/herd')->with('success', 'Animal added to herd.');
    }

    public function edit(int $id): string
    {
        $goat = $this->goats->find($id);
        if (! $goat) return redirect()->to('/admin/herd')->with('error', 'Animal not found.');
        return $this->dashboardView('admin/herd_form', [
            'pageTitle' => 'Edit — '.$goat['name'],
            'goat'      => $goat,
            'members'   => (new UserModel())->getByRole('member'),
        ]);
    }

    public function update(int $id)
    {
        $this->goats->update($id, [
            'name'      => $this->request->getPost('name'),
            'breed'     => $this->request->getPost('breed'),
            'sex'       => $this->request->getPost('sex'),
            'dob'       => $this->request->getPost('dob') ?: null,
            'pen_id'    => $this->request->getPost('pen_id'),
            'member_id' => $this->request->getPost('member_id') ?: null,
            'status'    => $this->request->getPost('status') ?? 'active',
            'notes'     => $this->request->getPost('notes'),
        ]);
        return redirect()->to('/admin/herd')->with('success', 'Animal record updated.');
    }
}
