<?php
declare(strict_types=1);
namespace App\Modules\Manager\Controllers;
use App\Controllers\BaseController;
use App\Models\GoatModel;
class ReportController extends BaseController
{
    public function index(): string
    {
        $goats = new GoatModel();
        $db    = \Config\Database::connect();
        return $this->dashboardView('manager/reports', [
            'pageTitle' => 'Reports',
            'stats'     => array_merge($goats->getStats(), [
                'births_this_month'    => $db->table('goats')->where("MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())")->countAllResults(),
                'mortality_this_month' => $db->table('goats')->where('status','deceased')->where("MONTH(updated_at)=MONTH(NOW())")->countAllResults(),
            ]),
        ]);
    }
    public function herd(): string   { return redirect()->to('/manager/reports'); }
    public function health(): string { return redirect()->to('/manager/reports'); }
    public function members(): string{ return redirect()->to('/manager/reports'); }
    public function export(string $type)
    {
        $db   = \Config\Database::connect();
        $data = match($type) {
            'herd'    => $db->table('goats g')->select('g.tag_number,g.name,g.breed,g.sex,g.pen_id,u.first_name,u.last_name')->join('users u','u.id=g.member_id','left')->where('g.status','active')->get()->getResultArray(),
            'members' => $db->table('users')->where('role','member')->where('deleted_at',null)->get()->getResultArray(),
            default   => [],
        };
        if (empty($data)) return redirect()->to('/manager/reports')->with('error','No data to export.');
        $csv  = implode(',', array_keys($data[0]))."\n";
        foreach ($data as $row) $csv .= implode(',', array_map(fn($v)=>'"'.str_replace('"','""',$v).'"', $row))."\n";
        return $this->response
            ->setHeader('Content-Type','text/csv')
            ->setHeader('Content-Disposition','attachment; filename="mdgoatco_'.$type.'_'.date('Y-m-d').'.csv"')
            ->setBody($csv);
    }
}
