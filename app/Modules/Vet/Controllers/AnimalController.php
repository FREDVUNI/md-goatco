<?php
declare(strict_types=1);
namespace App\Modules\Vet\Controllers;
use App\Controllers\BaseController;
use App\Models\GoatModel;
class AnimalController extends BaseController
{
    public function index(): string
    {
        return $this->dashboardView('vet/animals', ['pageTitle'=>'Animal Records','herd'=>(new GoatModel())->getFullHerd()]);
    }
    public function show(int $id): string
    {
        $goat = (new GoatModel())->find($id);
        if (! $goat) return redirect()->to('/vet/animals')->with('error','Animal not found.');
        return $this->dashboardView('vet/animal_detail', ['pageTitle'=>$goat['name'],'goat'=>$goat]);
    }
    public function lookup()
    {
        $tag  = $this->request->getGet('tag');
        $goat = \Config\Database::connect()->table('goats')->where('tag_number',$tag)->get()->getRowArray();
        return $this->response->setJSON($goat ?: ['error'=>'Not found']);
    }
}
