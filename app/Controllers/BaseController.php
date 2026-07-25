<?php
declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /** @var IncomingRequest|CLIRequest */
    protected $request;
    protected $helpers = ['url', 'form', 'html', 'text', 'goatco', 'email'];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);
    }

    // ── Session helpers ───────────────────────────────────────────────────
    protected function currentUser(): ?array
    {
        $s = session();
        if (! $s->has('user_id')) return null;
        return [
            'id'         => $s->get('user_id'),
            'role'       => $s->get('user_role'),
            'status'     => $s->get('user_status'),
            'first_name' => $s->get('user_first_name'),
            'last_name'  => $s->get('user_last_name'),
            'email'      => $s->get('user_email'),
        ];
    }

    protected function currentUserId(): int   { return (int)    session()->get('user_id');   }
    protected function currentUserRole(): string { return (string) session()->get('user_role'); }

    protected function startSession(array $user): void
    {
        session()->set([
            'user_id'         => $user['id'],
            'user_role'       => $user['role'],
            'user_status'     => $user['status'],
            'user_first_name' => $user['first_name'],
            'user_last_name'  => $user['last_name'],
            'user_email'      => $user['email'],
            'logged_in'       => true,
        ]);
    }

    // ── View helpers ──────────────────────────────────────────────────────
    protected function dashboardView(string $view, array $data = []): string
    {
        $data['currentUser'] = $this->currentUser();
        $data['role']        = $this->currentUserRole();
        $data['pageTitle']   = $data['pageTitle'] ?? 'Dashboard';

        // Notification bell — available on every dashboard page (not just
        // the unified /dashboard), so it's a single source of truth instead
        // of every controller having to remember to pass it.
        if (! isset($data['notifications']) && ! isset($data['unreadCount'])) {
            $notifs = new \App\Models\NotificationModel();
            $userId = $this->currentUserId();
            $data['notifications'] = $notifs->getForUser($userId, 10);
            $data['unreadCount']   = $notifs->getUnreadCount($userId);
        }

        return view($view, $data);
    }

    // ── Redirect helpers ──────────────────────────────────────────────────
    protected function redirectToDashboard(): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to('/dashboard');
    }

    // ── Listing helpers ────────────────────────────────────────────────────
    protected function searchTerm(): ?string
    {
        $q = trim((string) ($this->request->getGet('q') ?? ''));
        return $q === '' ? null : $q;
    }

    /**
     * Paginate a query builder that has already had its where/join/search
     * conditions applied but has NOT been limited or executed yet.
     * Use a non-default $group when more than one paginated table appears
     * on the same page, so each gets its own "page"/"page_{group}" param.
     *
     * @return array{0: array, 1: \CodeIgniter\Pager\Pager}
     */
    protected function paginateBuilder(\CodeIgniter\Database\BaseBuilder $builder, int $perPage = 15, string $group = 'default'): array
    {
        $selector = $group === 'default' ? 'page' : 'page_' . $group;
        $page     = max(1, (int) ($this->request->getGet($selector) ?? 1));
        $total    = $builder->countAllResults(false); // false = don't reset, builder stays usable
        $rows     = $builder->get($perPage, ($page - 1) * $perPage)->getResultArray();

        $pager = service('pager');
        $pager->store($group, $page, $perPage, $total);

        return [$rows, $pager];
    }

    /**
     * Reads the checked `ids[]` from a bulk-action form submission (see the
     * .bulk-table/.bulk-bar JS in dashboard.js), deduplicated and cast to int.
     */
    protected function bulkIds(): array
    {
        $ids = $this->request->getPost('ids');
        if (! is_array($ids)) return [];
        return array_values(array_unique(array_filter(array_map('intval', $ids))));
    }

    protected function downloadCsv(array $rows, string $filename): \CodeIgniter\HTTP\ResponseInterface
    {
        if (empty($rows)) {
            return redirect()->back()->with('error', 'No data to export.');
        }
        $csv = implode(',', array_keys($rows[0])) . "\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map(
                static fn ($v) => '"' . str_replace('"', '""', (string) $v) . '"',
                $row
            )) . "\n";
        }
        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    /**
     * Export rows as a branded .xlsx workbook: company logo, title, and a
     * styled/bordered header row over the data — replaces plain CSV exports
     * wherever a document needs to look like an official company export.
     */
    protected function downloadXlsx(array $rows, string $filename, string $title = ''): \CodeIgniter\HTTP\ResponseInterface
    {
        if (empty($rows)) {
            return redirect()->back()->with('error', 'No data to export.');
        }

        $columns  = array_keys($rows[0]);
        $colCount = count($columns);
        $lastCol  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $logoPath = FCPATH . 'img/logo.png';
        if (is_file($logoPath)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setPath($logoPath);
            $drawing->setHeight(42);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(4);
            $drawing->setOffsetY(4);
            $drawing->setWorksheet($sheet);
        }
        $sheet->getRowDimension(1)->setRowHeight(36);

        $title = $title !== '' ? $title : ucwords(str_replace(['_', '-'], ' ', pathinfo($filename, PATHINFO_FILENAME)));
        $sheet->setCellValue('B1', 'MD Goatco Farm — ' . $title);
        $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(15);
        $sheet->mergeCells('B1:' . $lastCol . '1');

        $sheet->setCellValue('B2', 'Exported ' . date('j M Y, g:i A'));
        $sheet->getStyle('B2')->getFont()->setItalic(true)->setSize(9)->getColor()->setRGB('6B7280');
        $sheet->mergeCells('B2:' . $lastCol . '2');

        $headerRowIdx = 4;
        foreach ($columns as $i => $col) {
            $cellRef = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1) . $headerRowIdx;
            $sheet->setCellValue($cellRef, ucwords(str_replace('_', ' ', $col)));
        }
        $headerRange = 'A' . $headerRowIdx . ':' . $lastCol . $headerRowIdx;
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('059669');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $r = $headerRowIdx + 1;
        foreach ($rows as $row) {
            foreach (array_values($row) as $i => $val) {
                $cellRef = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1) . $r;
                $sheet->setCellValueExplicit($cellRef, (string) ($val ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            }
            if ($r % 2 === 0) {
                $sheet->getStyle('A' . $r . ':' . $lastCol . $r)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F0FAF3');
            }
            $r++;
        }

        $dataRange = 'A' . $headerRowIdx . ':' . $lastCol . ($r - 1);
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
            ->getColor()->setRGB('D1E7DD');

        foreach (range(1, $colCount) as $i) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tmpPath = tempnam(sys_get_temp_dir(), 'xlsx_');
        $writer->save($tmpPath);
        $content = file_get_contents($tmpPath);
        unlink($tmpPath);

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($content);
    }

    /**
     * Parse an uploaded CSV/XLS/XLSX file into an array of associative rows
     * keyed by a normalized version of the header row (lowercase, spaces and
     * dashes collapsed to underscores) so callers can map columns loosely.
     *
     * @return array{0: array<int,array<string,string>>, 1: ?string} [$rows, $error]
     */
    protected function parseUploadedSpreadsheet(string $fieldName): array
    {
        $file = $this->request->getFile($fieldName);
        if (! $file || ! $file->isValid()) {
            return [[], 'Please choose a file to upload.'];
        }
        $ext = strtolower($file->getClientExtension() ?: $file->getExtension());
        if (! in_array($ext, ['csv', 'xls', 'xlsx'], true)) {
            return [[], 'Unsupported file type. Please upload a CSV or Excel (.xls/.xlsx) file.'];
        }

        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getTempName());
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getTempName());
            $sheet       = $spreadsheet->getActiveSheet();
            $grid        = $sheet->toArray(null, true, true, false);
        } catch (\Throwable $e) {
            return [[], 'Could not read that file: ' . $e->getMessage()];
        }

        if (empty($grid)) {
            return [[], 'The file appears to be empty.'];
        }

        $headers = array_map(static function ($h) {
            $h = strtolower(trim((string) $h));
            $h = preg_replace('/[\s\-]+/', '_', $h);
            return preg_replace('/[^a-z0-9_]/', '', $h);
        }, array_shift($grid));

        $rows = [];
        foreach ($grid as $line) {
            if (implode('', array_map('strval', $line)) === '') continue; // skip blank rows
            $row = [];
            foreach ($headers as $i => $key) {
                if ($key === '') continue;
                $row[$key] = trim((string) ($line[$i] ?? ''));
            }
            $rows[] = $row;
        }

        return [$rows, null];
    }

    /**
     * Run $rowHandler over every parsed row, catching per-row failures so one
     * bad row doesn't abort the whole import. $rowHandler returns true on
     * success or an error message string to record and skip that row.
     *
     * @return array{0: int, 1: array<int,string>} [$createdCount, $errorMessages]
     */
    protected function processImportRows(array $rows, callable $rowHandler): array
    {
        $created = 0;
        $errors  = [];
        foreach ($rows as $i => $row) {
            try {
                $result = $rowHandler($row);
            } catch (\Throwable $e) {
                $result = $e->getMessage();
            }
            if ($result === true) {
                $created++;
            } else {
                $errors[] = 'Row ' . ($i + 2) . ': ' . (string) $result;
            }
        }
        return [$created, $errors];
    }

    protected function importRedirect(string $to, int $created, array $errors): \CodeIgniter\HTTP\RedirectResponse
    {
        $redirect = redirect()->to($to);
        $msg      = $created . ' row' . ($created === 1 ? '' : 's') . ' imported.';
        if (empty($errors)) {
            return $redirect->with('success', $msg);
        }
        $preview = array_slice($errors, 0, 5);
        $msg .= ' ' . count($errors) . ' row' . (count($errors) === 1 ? '' : 's') . ' skipped — ' . implode('; ', $preview);
        if (count($errors) > 5) {
            $msg .= '; +' . (count($errors) - 5) . ' more';
        }
        return $redirect->with($created > 0 ? 'success' : 'error', $msg);
    }
}
