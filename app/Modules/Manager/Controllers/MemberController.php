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
        $db = \Config\Database::connect();
        $members = $db->table('users u')
            ->select('u.*, COUNT(g.id) as goat_count')
            ->join('goats g','g.member_id=u.id AND g.status="active"','left')
            ->where('u.role','member')->where('u.deleted_at',null)
            ->groupBy('u.id')->orderBy('u.created_at','DESC')
            ->get()->getResultArray();
        return $this->dashboardView('manager/members', ['pageTitle'=>'Members','members'=>$members]);
    }
    public function show(int $id): string { return redirect()->to('/manager/members'); }
}
