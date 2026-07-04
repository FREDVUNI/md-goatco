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
}
