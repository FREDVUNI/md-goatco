<?php
declare(strict_types=1);
namespace App\Modules\Member\Controllers;
use App\Controllers\BaseController;
use App\Models\GoatModel;
use App\Models\WeightLogModel;
class PortfolioController extends BaseController
{
    public function index(): string
    {
        $memberId  = $this->currentUserId();
        $goatCount = count((new GoatModel())->getWithLatestWeight($memberId));
        $search    = $this->searchTerm();
        [$goats, $pager] = $this->paginateBuilder((new GoatModel())->getWithLatestWeightQuery($memberId, $search));

        return $this->dashboardView('member/portfolio', [
            'pageTitle' => 'My Goats',
            'goats'     => $goats,
            'pager'     => $pager,
            'search'    => $search,
            'goatCount' => $goatCount,
        ]);
    }
    public function show(int $id)
    {
        $goat = (new GoatModel())->find($id);
        if (! $goat || (int) $goat['member_id'] !== $this->currentUserId()) return redirect()->to('/member/goats')->with('error','Goat not found.');

        $db = \Config\Database::connect();
        $goat['is_flagged'] = (bool) $db->table('vet_visits')
            ->where('goat_id', $id)->where('is_flagged', 1)->where('flag_resolved_at', null)
            ->countAllResults();

        $weightHistory = (new WeightLogModel())->getForGoat($id);
        $healthHistory = $db->table('vet_visits v')
            ->select('v.*, u.first_name, u.last_name')
            ->join('users u', 'u.id=v.vet_id', 'left')
            ->where('v.goat_id', $id)
            ->orderBy('v.visit_date', 'DESC')
            ->get()->getResultArray();

        return $this->dashboardView('member/goat_profile', [
            'pageTitle'     => $goat['name'],
            'goat'          => $goat,
            'latestWeight'  => $weightHistory[0] ?? null,
            'weightHistory' => $weightHistory,
            'healthHistory' => $healthHistory,
        ]);
    }
}
