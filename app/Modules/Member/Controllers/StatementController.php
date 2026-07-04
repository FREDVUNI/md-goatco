<?php
declare(strict_types=1);
namespace App\Modules\Member\Controllers;
use App\Controllers\BaseController;
use App\Models\TransactionModel;
class StatementController extends BaseController
{
    public function index(): string
    {
        $txns   = new TransactionModel();
        $uid    = $this->currentUserId();
        $search = $this->searchTerm();
        [$transactions, $pager] = $this->paginateBuilder($txns->getByMemberQuery($uid, $search));

        return $this->dashboardView('member/statements', [
            'pageTitle'    => 'Statements',
            'transactions' => $transactions,
            'pager'        => $pager,
            'search'       => $search,
            'balance'      => $txns->getCurrentBalance($uid),
            'totalCredited'=> $txns->getTotalCredited($uid),
        ]);
    }

    public function download()
    {
        $uid  = $this->currentUserId();
        $txns = new TransactionModel();
        $user = $this->currentUser();

        $html = view('member/statement_pdf', [
            'user'          => $user,
            'transactions'  => $txns->getByMember($uid),
            'balance'       => $txns->getCurrentBalance($uid),
            'totalCredited' => $txns->getTotalCredited($uid),
            'generatedAt'   => date('j M Y, g:i A'),
        ]);

        // dompdf 2.x emits PHP 8.4 implicit-nullable-parameter deprecation notices;
        // with display_errors on they'd otherwise leak into the PDF binary output.
        $previousReporting = error_reporting(E_ALL & ~E_DEPRECATED);
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        error_reporting($previousReporting);

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="statement_' . date('Y-m-d') . '.pdf"')
            ->setBody($dompdf->output());
    }
}
