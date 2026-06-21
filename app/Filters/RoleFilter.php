<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * RoleFilter
 *
 * Usage in Routes.php:
 *   ['filter' => 'role:super_admin']
 *   ['filter' => 'role:vet,manager,super_admin']
 *
 * Checks:
 *  1. User is logged in (session exists)
 *  2. User's role matches one of the allowed roles
 *  3. For member role: account status must be 'active' (approved by admin)
 */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null): mixed
    {
        $session = session();

        // Not logged in → redirect to the right login page
        if (! $session->has('user_id')) {
            return redirect()->to($this->loginUrl($arguments))->with('error', 'Please log in to continue.');
        }

        $userRole   = $session->get('user_role');
        $userStatus = $session->get('user_status');

        // No allowed roles specified — just require login
        if (empty($arguments)) {
            return null;
        }

        $allowedRoles = is_array($arguments) ? $arguments : explode(',', $arguments[0] ?? '');
        $allowedRoles = array_map('trim', $allowedRoles);

        // Role not in allowed list
        if (! in_array($userRole, $allowedRoles, true)) {
            return redirect()->to($this->dashboardUrl($userRole))
                             ->with('error', 'You do not have permission to access that area.');
        }

        // Members must be approved before accessing their dashboard
        if ($userRole === 'member' && $userStatus !== 'active') {
            $session->destroy();

            $message = match ($userStatus) {
                'pending'  => 'Your application is still under review. You will receive an email when approved.',
                'rejected' => 'Your application was not approved. Please contact hello@mdgoatco.farm.',
                default    => 'Your account is inactive. Please contact support.',
            };

            return redirect()->to('/auth/login')->with('warning', $message);
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): mixed
    {
        return null;
    }

    /**
     * Determine the correct login URL based on which roles are expected.
     */
    private function loginUrl(mixed $arguments): string
    {
        if (empty($arguments)) {
            return '/auth/login';
        }

        $roles = is_array($arguments) ? $arguments : explode(',', $arguments[0] ?? '');

        if (in_array('super_admin', $roles, true)) {
            return '/auth/admin';
        }
        if (in_array('manager', $roles, true)) {
            return '/auth/manager';
        }
        if (in_array('vet', $roles, true)) {
            return '/auth/vet';
        }

        return '/auth/login';
    }

    /**
     * Redirect already-logged-in users to their own dashboard.
     */
    private function dashboardUrl(string $role): string
    {
        return match ($role) {
            'super_admin' => '/admin/dashboard',
            'manager'     => '/manager/dashboard',
            'vet'         => '/vet/dashboard',
            'member'      => '/member/dashboard',
            default       => '/',
        };
    }
}
