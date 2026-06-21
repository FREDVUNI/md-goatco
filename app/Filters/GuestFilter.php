<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * GuestFilter
 *
 * If a user is already logged in and hits a login page,
 * send them straight to their dashboard.
 */
class GuestFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null): mixed
    {
        $session = session();

        if ($session->has('user_id')) {
            $role = $session->get('user_role');

            return redirect()->to(match ($role) {
                'super_admin' => '/admin/dashboard',
                'manager'     => '/manager/dashboard',
                'vet'         => '/vet/dashboard',
                'member'      => '/member/dashboard',
                default       => '/',
            });
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): mixed
    {
        return null;
    }
}
