<?php
declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null): mixed
    {
        $session = session();

        if (! $session->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please log in to continue.');
        }

        $userRole   = $session->get('user_role');
        $userStatus = $session->get('user_status');

        if (empty($arguments)) return null;

        $allowedRoles = is_array($arguments) ? $arguments : explode(',', $arguments[0] ?? '');
        $allowedRoles = array_map('trim', $allowedRoles);

        if (! in_array($userRole, $allowedRoles, true)) {
            return redirect()->to('/dashboard')
                             ->with('error', 'You do not have permission to access that area.');
        }

        // Members with pending/rejected status cannot access their dashboard
        if ($userRole === 'member' && ! in_array($userStatus, ['active'], true)) {
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
}
