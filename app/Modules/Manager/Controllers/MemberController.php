<?php
declare(strict_types=1);
namespace App\Modules\Manager\Controllers;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\GoatModel;
class MemberController extends BaseController
{
    public function index(): string
    {
        $search  = $this->searchTerm();
        $builder = \Config\Database::connect()->table('users u')
            ->select('u.id,u.email,u.phone,u.status,u.first_name,u.last_name,u.last_login_at,u.created_at, COUNT(g.id) as goat_count')
            ->join('goats g','g.member_id=u.id AND g.status="active"','left')
            ->where('u.role','member')->where('u.deleted_at',null)
            ->groupBy('u.id')->orderBy('u.created_at','DESC');
        if ($search) {
            $builder->groupStart()->like('u.first_name',$search)->orLike('u.last_name',$search)->orLike('u.phone',$search)->groupEnd();
        }
        [$members, $pager] = $this->paginateBuilder($builder);

        return $this->dashboardView('manager/members', [
            'pageTitle' => 'Members',
            'members'   => $members,
            'pager'     => $pager,
            'search'    => $search,
        ]);
    }
    public function show(int $id): string { return redirect()->to('/manager/members'); }
}
