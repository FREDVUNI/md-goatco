<?php
declare(strict_types=1);
namespace App\Modules\Manager\Controllers;
use App\Controllers\BaseController;
class ScheduleController extends BaseController
{
    public function index(): string
    {
        $db    = \Config\Database::connect();
        $tasks = $db->table('vet_schedules')->orderBy('scheduled_at','ASC')->get()->getResultArray();
        $vets  = (new \App\Models\UserModel())->getByRole('vet');
        return $this->dashboardView('manager/schedule', ['pageTitle'=>'Vet Schedule','tasks'=>$tasks,'vets'=>$vets]);
    }
    public function create(): string
    {
        $vets = (new \App\Models\UserModel())->getByRole('vet');
        return $this->dashboardView('manager/schedule_create', ['pageTitle'=>'Add Task','vets'=>$vets]);
    }
    public function store()
    {
        if (! $this->validate(['task'=>'required','scheduled_at'=>'required'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        \Config\Database::connect()->table('vet_schedules')->insert([
            'task'=>$this->request->getPost('task'),'description'=>$this->request->getPost('description'),
            'animals_desc'=>$this->request->getPost('animals_desc'),'scheduled_at'=>$this->request->getPost('scheduled_at'),
            'assigned_vet_id'=>$this->request->getPost('assigned_vet_id')?:null,'status'=>'scheduled',
        ]);
        return redirect()->to('/manager/schedule')->with('success','Task scheduled.');
    }
    public function complete(int $id)
    {
        \Config\Database::connect()->table('vet_schedules')->where('id',$id)->update(['status'=>'completed','completed_at'=>date('Y-m-d H:i:s')]);
        return redirect()->to('/manager/schedule')->with('success','Task marked as completed.');
    }
    public function delete(int $id)
    {
        \Config\Database::connect()->table('vet_schedules')->where('id',$id)->update(['status'=>'cancelled']);
        return redirect()->to('/manager/schedule')->with('success','Task cancelled.');
    }
}
