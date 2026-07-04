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
        $flags = $this->visits->getMyActiveFlags($this->currentUserId());
        return $this->dashboardView('vet/flags', ['pageTitle'=>'My Health Flags','flags'=>$flags,'flagCount'=>count($flags)]);
    }
    public function resolve(int $id)
    {
        $this->visits->update($id, ['flag_resolved_at'=>date('Y-m-d H:i:s')]);
        return redirect()->to('/vet/flags')->with('success','Flag resolved.');
    }
}
