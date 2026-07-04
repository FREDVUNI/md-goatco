<?php
declare(strict_types=1);
namespace App\Modules\Vet\Controllers;
use App\Controllers\BaseController;
use App\Models\VetVisitModel;
use App\Models\GoatModel;
use App\Models\WeightLogModel;
use App\Models\UserModel;
use App\Libraries\EmailService;

class VisitController extends BaseController
{
    private VetVisitModel $visits;
    public function __construct() { $this->visits = new VetVisitModel(); }

    public function create(): string
    {
        return $this->dashboardView('vet/visit_log', ['pageTitle'=>'Log a Visit','goats'=>(new GoatModel())->getFullHerd()]);
    }

    public function store()
    {
        if (! $this->validate(['goat_id'=>'required','visit_date'=>'required|valid_date[Y-m-d]','visit_type'=>'required'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $isFlagged = (bool)$this->request->getPost('is_flagged');
        $visitId   = $this->visits->insert([
            'goat_id'         => $this->request->getPost('goat_id'),
            'vet_id'          => $this->currentUserId(),
            'visit_date'      => $this->request->getPost('visit_date'),
            'visit_type'      => $this->request->getPost('visit_type'),
            'clinical_notes'  => $this->request->getPost('clinical_notes'),
            'treatment'       => $this->request->getPost('treatment'),
            'outcome'         => $this->request->getPost('outcome') ?? 'unknown',
            'weight_recorded' => $this->request->getPost('weight_recorded') ?: null,
            'is_flagged'      => $isFlagged ? 1 : 0,
            'flag_reason'     => $isFlagged ? $this->request->getPost('flag_reason') : null,
            'followup_date'   => $this->request->getPost('followup_date') ?: null,
        ]);
        if ($this->request->getPost('weight_recorded')) {
            (new WeightLogModel())->insert([
                'goat_id'=>$this->request->getPost('goat_id'),'weight'=>$this->request->getPost('weight_recorded'),
                'logged_at'=>date('Y-m-d H:i:s'),'logged_by'=>$this->currentUserId(),
            ]);
        }
        if ($isFlagged) {
            $goats = new GoatModel();
            $goat  = $goats->find($this->request->getPost('goat_id'));
            if ($goat && $goat['member_id']) {
                $member  = (new UserModel())->find($goat['member_id']);
                $reason  = $this->request->getPost('flag_reason') ?? '';
                try {
                    $mailer = new EmailService();
                    if ($member) $mailer->sendHealthAlert($member, $goat, $reason);
                    foreach ((new UserModel())->getByRole('manager') as $mgr) {
                        $mailer->sendHealthFlagAlert($mgr, $goat, $reason);
                    }
                } catch (\Throwable $e) {}
            }
        }
        return redirect()->to('/vet/visits/history')->with('success','Visit logged successfully.');
    }

    public function history(): string
    {
        $vetId  = $this->currentUserId();
        $search = $this->searchTerm();
        [$visits, $pager] = $this->paginateBuilder($this->visits->getByVetQuery($vetId, $search));

        return $this->dashboardView('vet/visit_history', [
            'pageTitle' => 'Visit History',
            'visits'    => $visits,
            'pager'     => $pager,
            'search'    => $search,
        ]);
    }

    public function export()
    {
        $rows = $this->visits->getByVetQuery($this->currentUserId(), $this->searchTerm())->get()->getResultArray();
        return $this->downloadCsv($rows, 'visit_history_' . date('Y-m-d') . '.csv');
    }

    public function show(int $id): string { return redirect()->to('/vet/visits/history'); }
}
