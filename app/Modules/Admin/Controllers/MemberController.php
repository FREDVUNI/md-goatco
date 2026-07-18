<?php
declare(strict_types=1);
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\GoatModel;

class MemberController extends BaseController
{
    private UserModel $users;
    public function __construct() { $this->users = new UserModel(); }

    private function membersQuery(?string $search): \CodeIgniter\Database\BaseBuilder
    {
        $builder = \Config\Database::connect()->table('users u')
            ->select('u.id,u.email,u.phone,u.status,u.first_name,u.last_name,u.last_login_at,u.created_at, COUNT(g.id) as goat_count')
            ->join('goats g','g.member_id=u.id AND g.status="active"','left')
            ->where('u.role','member')->where('u.deleted_at',null)
            ->groupBy('u.id')->orderBy('u.created_at','DESC');
        if ($search) {
            $builder->groupStart()->like('u.first_name',$search)->orLike('u.last_name',$search)->orLike('u.email',$search)->orLike('u.phone',$search)->groupEnd();
        }
        return $builder;
    }

    public function index(): string
    {
        $search = $this->searchTerm();
        [$members, $pager] = $this->paginateBuilder($this->membersQuery($search));

        return $this->dashboardView('admin/members', [
            'pageTitle'   => 'Members',
            'members'     => $members,
            'pager'       => $pager,
            'search'      => $search,
            'pendingCount'=> (new \App\Models\MemberApplicationModel())->countPending(),
        ]);
    }

    public function export()
    {
        $rows = $this->membersQuery($this->searchTerm())->get()->getResultArray();
        return $this->downloadCsv($rows, 'members_' . date('Y-m-d') . '.csv');
    }

    public function show(int $id)
    {
        $user = $this->users->find($id);
        if (! $user || $user['role'] !== 'member') return redirect()->to('/admin/members')->with('error','Member not found.');
        return $this->dashboardView('admin/member_detail', [
            'pageTitle' => $user['first_name'].' '.$user['last_name'],
            'member'    => $user,
            'goats'     => (new GoatModel())->getByMember($id),
        ]);
    }

    public function deactivate(int $id)
    {
        $user = $this->users->find($id);
        $this->users->deactivate($id);
        return redirect()->to('/admin/members')->with('success', ($user['first_name']??'Member').'\'s account deactivated.');
    }

    public function reactivate(int $id)
    {
        $user = $this->users->find($id);
        $this->users->activate($id);
        return redirect()->to('/admin/members')->with('success', ($user['first_name']??'Member').'\'s account reactivated.');
    }
}
