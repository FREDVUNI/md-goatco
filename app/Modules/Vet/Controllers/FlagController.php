<?php
declare(strict_types=1);
namespace App\Modules\Vet\Controllers;
use App\Controllers\BaseController;
use App\Models\VetVisitModel;
class FlagController extends BaseController
{
    private VetVisitModel $visits;
    public function __construct() { $this->visits = new VetVisitModel(); }
    public function index(): string
    {
        $vetId     = $this->currentUserId();
        $flagCount = count($this->visits->getMyActiveFlags($vetId));
        $search    = $this->searchTerm();
        [$flags, $pager] = $this->paginateBuilder($this->visits->getMyActiveFlagsQuery($vetId, $search));

        return $this->dashboardView('vet/flags', [
            'pageTitle' => 'My Health Flags',
            'flags'     => $flags,
            'pager'     => $pager,
            'search'    => $search,
            'flagCount' => $flagCount,
        ]);
    }
    public function resolve(int $id)
    {
        $this->visits->update($id, ['flag_resolved_at'=>date('Y-m-d H:i:s')]);
        return redirect()->to('/vet/flags')->with('success','Flag resolved.');
    }
}
