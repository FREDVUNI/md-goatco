<?php
declare(strict_types=1);
namespace App\Modules\Manager\Controllers;
use App\Controllers\BaseController;
use App\Models\VetVisitModel;
class HealthController extends BaseController
{
    private VetVisitModel $visits;
    public function __construct() { $this->visits = new VetVisitModel(); }
    public function index(): string
    {
        $flagCount = count($this->visits->getActiveFlags());
        $search    = $this->searchTerm();
        [$flags, $pager] = $this->paginateBuilder($this->visits->getActiveFlagsQuery($search));

        return $this->dashboardView('manager/health', [
            'pageTitle' => 'Health Flags',
            'flags'     => $flags,
            'pager'     => $pager,
            'search'    => $search,
            'flagCount' => $flagCount,
        ]);
    }

    public function export()
    {
        $rows = $this->visits->getActiveFlagsQuery($this->searchTerm())->get()->getResultArray();
        return $this->downloadXlsx($rows, 'health_flags_' . date('Y-m-d') . '.xlsx', 'Health Flags');
    }

    public function show(int $id) { return redirect()->to('/manager/health'); }
    public function resolve(int $id)
    {
        $this->visits->update($id, ['flag_resolved_at'=>date('Y-m-d H:i:s')]);
        return redirect()->to('/manager/health')->with('success','Flag marked as resolved.');
    }

    public function bulkResolve()
    {
        $ids = $this->bulkIds();
        foreach ($ids as $id) $this->visits->update($id, ['flag_resolved_at'=>date('Y-m-d H:i:s')]);
        return redirect()->to('/manager/health')->with('success', count($ids).' flag'.(count($ids)===1?'':'s').' marked as resolved.');
    }
}
