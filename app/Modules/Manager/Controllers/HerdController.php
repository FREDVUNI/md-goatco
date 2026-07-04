<?php
declare(strict_types=1);
namespace App\Modules\Manager\Controllers;
use App\Controllers\BaseController;
use App\Models\GoatModel;
class HerdController extends BaseController
{
    private GoatModel $goats;
    public function __construct() { $this->goats = new GoatModel(); }
    public function index(): string
    {
        return $this->dashboardView('manager/herd', ['pageTitle'=>'Herd Registry','herd'=>$this->goats->getFullHerd(),'stats'=>$this->goats->getStats()]);
    }
    public function show(int $id): string { return redirect()->to('/manager/herd'); }
    public function create(): string { return $this->dashboardView('manager/herd_form', ['pageTitle'=>'Add Animal']); }
    public function store() { return redirect()->to('/manager/herd')->with('info','Feature coming soon.'); }
    public function edit(int $id): string { return redirect()->to('/manager/herd'); }
    public function update(int $id) { return redirect()->to('/manager/herd'); }
}
