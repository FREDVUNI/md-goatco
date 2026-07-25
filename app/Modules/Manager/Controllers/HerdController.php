<?php
declare(strict_types=1);
namespace App\Modules\Manager\Controllers;
use App\Controllers\BaseController;
use App\Models\GoatModel;
use App\Models\UserModel;
class HerdController extends BaseController
{
    private GoatModel $goats;
    public function __construct() { $this->goats = new GoatModel(); }
    public function index(): string
    {
        $search = $this->searchTerm();
        [$herd, $pager] = $this->paginateBuilder($this->goats->getFullHerdQuery($search));

        return $this->dashboardView('manager/herd', [
            'pageTitle' => 'Herd Registry',
            'herd'      => $herd,
            'pager'     => $pager,
            'search'    => $search,
            'stats'     => $this->goats->getStats(),
        ]);
    }

    public function export()
    {
        $rows = $this->goats->getFullHerdQuery($this->searchTerm())->get()->getResultArray();
        return $this->downloadXlsx($rows, 'herd_' . date('Y-m-d') . '.xlsx', 'Herd Registry');
    }

    public function import()
    {
        [$rows, $error] = $this->parseUploadedSpreadsheet('file');
        if ($error) return redirect()->back()->with('error', $error);

        $users = new UserModel();
        [$created, $errors] = $this->processImportRows($rows, function (array $row) use ($users) {
            $tag = strtoupper(trim($row['tag_number'] ?? ''));
            if ($tag === '') return 'tag_number is required';
            if ($this->goats->where('tag_number', $tag)->first()) return "tag_number '$tag' already exists";
            $name = trim($row['name'] ?? '');
            if ($name === '') return 'name is required';
            $sex = strtolower(trim($row['sex'] ?? ''));
            if ($sex !== '' && ! in_array($sex, ['male', 'female'], true)) return "sex must be male or female, got '$sex'";
            $memberId = null;
            if (! empty($row['member_email'])) {
                $member = $users->where('email', trim($row['member_email']))->first();
                if (! $member) return "member_email not found: {$row['member_email']}";
                $memberId = $member['id'];
            }
            $this->goats->insert([
                'tag_number' => $tag,
                'name'       => $name,
                'breed'      => ($row['breed'] ?? '') ?: null,
                'sex'        => $sex ?: null,
                'dob'        => ($row['dob'] ?? '') ?: null,
                'pen_id'     => ($row['pen_id'] ?? '') ?: null,
                'member_id'  => $memberId,
                'status'     => 'active',
                'notes'      => $row['notes'] ?? null,
            ]);
            return true;
        });
        return $this->importRedirect('/manager/herd', $created, $errors);
    }

    public function show(int $id) { return redirect()->to('/manager/herd'); }

    public function create()
    {
        return $this->dashboardView('manager/herd_form', [
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
        return redirect()->to('/manager/herd')->with('success', 'Animal added to herd.');
    }

    public function edit(int $id)
    {
        $goat = $this->goats->find($id);
        if (! $goat) return redirect()->to('/manager/herd')->with('error', 'Animal not found.');
        return $this->dashboardView('manager/herd_form', [
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
        return redirect()->to('/manager/herd')->with('success', 'Animal record updated.');
    }
}
