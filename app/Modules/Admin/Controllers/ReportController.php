<?php
declare(strict_types=1);
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;
use App\Models\MemberApplicationModel;

class ReportController extends BaseController
{
    /** [DateTimeImmutable $start, DateTimeImmutable $end, string $label] for a range type + offset (0 = current period). */
    private function periodBounds(string $range, int $offset): array
    {
        $now = new \DateTimeImmutable('now');
        switch ($range) {
            case 'week':
                $start = $now->modify('monday this week')->setTime(0, 0, 0)->modify(($offset * 7) . ' days');
                $end   = $start->modify('+7 days');
                $label = $start->format('j M') . ' – ' . $end->modify('-1 day')->format('j M Y');
                break;
            case 'year':
                $year  = (int) $now->format('Y') + $offset;
                $start = new \DateTimeImmutable("$year-01-01 00:00:00");
                $end   = $start->modify('+1 year');
                $label = (string) $year;
                break;
            case 'month':
            default:
                $start = $now->modify('first day of this month')->setTime(0, 0, 0)->modify($offset . ' months');
                $end   = $start->modify('+1 month');
                $label = $start->format('F Y');
                break;
        }
        return [$start, $end, $label];
    }

    private function currentRange(): string
    {
        $range = $this->request->getGet('range');
        return in_array($range, ['week', 'month', 'year'], true) ? $range : 'month';
    }

    public function index(): string
    {
        $range  = $this->currentRange();
        $offset = (int) ($this->request->getGet('offset') ?? 0);
        [$start, $end, $label] = $this->periodBounds($range, $offset);
        $startStr = $start->format('Y-m-d H:i:s');
        $endStr   = $end->format('Y-m-d H:i:s');

        $db = \Config\Database::connect();
        $stats = [
            'applications_submitted' => $db->table('member_applications')->where('created_at >=', $startStr)->where('created_at <', $endStr)->countAllResults(),
            'applications_approved'  => $db->table('member_applications')->where('status', 'approved')->where('reviewed_at >=', $startStr)->where('reviewed_at <', $endStr)->countAllResults(),
            'applications_rejected'  => $db->table('member_applications')->where('status', 'rejected')->where('reviewed_at >=', $startStr)->where('reviewed_at <', $endStr)->countAllResults(),
            'new_members'            => $db->table('users')->where('role', 'member')->where('created_at >=', $startStr)->where('created_at <', $endStr)->countAllResults(),
            'new_goats'              => $db->table('goats')->where('created_at >=', $startStr)->where('created_at <', $endStr)->countAllResults(),
            'health_flags_raised'    => $db->table('vet_visits')->where('is_flagged', 1)->where('visit_date >=', $startStr)->where('visit_date <', $endStr)->countAllResults(),
            'wallet_credited'        => (float) ($db->table('transactions')->selectSum('amount')->where('type', 'credit')->where('created_at >=', $startStr)->where('created_at <', $endStr)->get()->getRow()->amount ?? 0),
            'wallet_debited'         => (float) ($db->table('transactions')->selectSum('amount')->where('type', 'debit')->where('created_at >=', $startStr)->where('created_at <', $endStr)->get()->getRow()->amount ?? 0),
        ];

        $activity = $this->activityLog($startStr, $endStr);

        return $this->dashboardView('admin/reports', [
            'pageTitle'    => 'Reports & Audit',
            'range'        => $range,
            'offset'       => $offset,
            'periodLabel'  => $label,
            'stats'        => $stats,
            'activity'     => array_slice($activity, 0, 100),
            'activityTotal'=> count($activity),
            'pendingCount' => (new MemberApplicationModel())->countPending(),
        ]);
    }

    public function export()
    {
        $range  = $this->currentRange();
        $offset = (int) ($this->request->getGet('offset') ?? 0);
        [$start, $end, $label] = $this->periodBounds($range, $offset);

        $rows = $this->activityLog($start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s'));
        if (empty($rows)) {
            return redirect()->back()->with('error', 'No activity to export for this period.');
        }
        foreach ($rows as &$r) {
            $r['date'] = date('j M Y, g:i A', strtotime($r['date']));
        }
        unset($r);

        $filename = 'mdgoatco_audit_' . $range . '_' . $start->format('Y-m-d') . '.xlsx';
        return $this->downloadXlsx($rows, $filename, 'Audit Report — ' . ucfirst($range) . ' (' . $label . ')');
    }

    /** A single chronological activity log — applications, reviews, new members, wallet transactions, health flags. */
    private function activityLog(string $startStr, string $endStr): array
    {
        $db   = \Config\Database::connect();
        $rows = [];

        $rows = array_merge($rows, $db->table('member_applications')
            ->select("created_at as date, 'Application' as type, CONCAT('Application submitted — ', first_name, ' ', last_name) as description")
            ->where('created_at >=', $startStr)->where('created_at <', $endStr)
            ->get()->getResultArray());

        $rows = array_merge($rows, $db->table('member_applications ma')
            ->select("ma.reviewed_at as date, 'Review' as type, CONCAT('Application ', ma.status, ' — ', ma.first_name, ' ', ma.last_name, IFNULL(CONCAT(' (by ', u.first_name, ' ', u.last_name, ')'), '')) as description")
            ->join('users u', 'u.id = ma.reviewed_by', 'left')
            ->where('ma.reviewed_at >=', $startStr)->where('ma.reviewed_at <', $endStr)
            ->where('ma.reviewed_at IS NOT NULL')
            ->get()->getResultArray());

        $rows = array_merge($rows, $db->table('users')
            ->select("created_at as date, 'Member' as type, CONCAT('Member joined — ', first_name, ' ', last_name) as description")
            ->where('role', 'member')->where('created_at >=', $startStr)->where('created_at <', $endStr)
            ->get()->getResultArray());

        $rows = array_merge($rows, $db->table('transactions t')
            ->select("t.created_at as date, 'Wallet' as type, CONCAT(UPPER(LEFT(t.type,1)), SUBSTRING(t.type,2), ' — UGX ', FORMAT(t.amount,0), ' — ', IFNULL(u.first_name,'—'), ' ', IFNULL(u.last_name,''), ' (', t.description, ')') as description")
            ->join('users u', 'u.id = t.member_id', 'left')
            ->where('t.created_at >=', $startStr)->where('t.created_at <', $endStr)
            ->get()->getResultArray());

        $rows = array_merge($rows, $db->table('vet_visits v')
            ->select("v.visit_date as date, 'Health' as type, CONCAT('Health flag raised — ', g.name, ' (', g.tag_number, '): ', IFNULL(v.flag_reason,'no reason given')) as description")
            ->join('goats g', 'g.id = v.goat_id')
            ->where('v.is_flagged', 1)->where('v.visit_date >=', $startStr)->where('v.visit_date <', $endStr)
            ->get()->getResultArray());

        usort($rows, static fn ($a, $b) => strcmp((string) ($b['date'] ?? ''), (string) ($a['date'] ?? '')));
        return $rows;
    }
}
