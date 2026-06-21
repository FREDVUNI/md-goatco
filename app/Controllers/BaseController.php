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
    protected $request;

    protected $helpers = ['url', 'form', 'html', 'text'];

    public function initController(
        RequestInterface  $request,
        ResponseInterface $response,
        LoggerInterface   $logger
    ): void {
        parent::initController($request, $response, $logger);
    }

    // ── Session helpers ───────────────────────────────────────────────────────

    protected function currentUser(): ?array
    {
        $session = session();
        if (! $session->has('user_id')) {
            return null;
        }

        return [
            'id'         => $session->get('user_id'),
            'role'       => $session->get('user_role'),
            'status'     => $session->get('user_status'),
            'first_name' => $session->get('user_first_name'),
            'last_name'  => $session->get('user_last_name'),
            'email'      => $session->get('user_email'),
        ];
    }

    protected function currentUserId(): int
    {
        return (int) session()->get('user_id');
    }

    protected function currentUserRole(): string
    {
        return (string) session()->get('user_role');
    }

    /**
     * Start a session for a logged-in user.
     */
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

    // ── View helpers ──────────────────────────────────────────────────────────

    /**
     * Render a dashboard view with the correct layout.
     */
    protected function dashboardView(string $view, array $data = []): string
    {
        $data['currentUser'] = $this->currentUser();
        $data['role']        = $this->currentUserRole();
        $data['pageTitle']   = $data['pageTitle'] ?? 'Dashboard';

        return view($view, $data);
    }

    // ── Redirect helpers ──────────────────────────────────────────────────────

    protected function redirectToDashboard(): \CodeIgniter\HTTP\RedirectResponse
    {
        return redirect()->to(match ($this->currentUserRole()) {
            'super_admin' => '/admin/dashboard',
            'manager'     => '/manager/dashboard',
            'vet'         => '/vet/dashboard',
            'member'      => '/member/dashboard',
            default       => '/',
        });
    }
}
