<?php

declare(strict_types=1);

namespace App\Modules\Api\Controllers\V1;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

// ══════════════════════════════════════════════════════════════════════════════
// BASE API CONTROLLER
// Every API controller extends this instead of BaseController
// ══════════════════════════════════════════════════════════════════════════════

abstract class BaseApiController extends Controller
{
    protected array $helpers = [];

    public function initController(
        RequestInterface  $request,
        ResponseInterface $response,
        LoggerInterface   $logger
    ): void {
        parent::initController($request, $response, $logger);
    }

    // ── Standard JSON responses ───────────────────────────────────────────────

    protected function ok(mixed $data = null, string $message = 'Success', int $code = 200): ResponseInterface
    {
        return $this->response->setStatusCode($code)->setJSON([
            'status'  => 'success',
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
        ]);
    }

    protected function created(mixed $data = null, string $message = 'Created'): ResponseInterface
    {
        return $this->ok($data, $message, 201);
    }

    protected function error(string $message, int $code = 400, mixed $errors = null): ResponseInterface
    {
        $body = ['status' => 'error', 'code' => $code, 'message' => $message];
        if ($errors !== null) {
            $body['errors'] = $errors;
        }

        return $this->response->setStatusCode($code)->setJSON($body);
    }

    protected function unauthorized(string $message = 'Unauthorized'): ResponseInterface
    {
        return $this->error($message, 401);
    }

    protected function forbidden(string $message = 'Forbidden'): ResponseInterface
    {
        return $this->error($message, 403);
    }

    protected function notFound(string $message = 'Resource not found'): ResponseInterface
    {
        return $this->error($message, 404);
    }

    // ── Auth helpers ──────────────────────────────────────────────────────────

    /**
     * Get the authenticated user from the JWT payload
     * (set on $request by JwtFilter).
     */
    protected function authUser(): ?object
    {
        return $this->request->authUser ?? null;
    }

    protected function authUserId(): int
    {
        return (int) ($this->authUser()->sub ?? 0);
    }

    protected function authUserRole(): string
    {
        return (string) ($this->authUser()->role ?? '');
    }

    protected function requireRole(string ...$roles): bool
    {
        return in_array($this->authUserRole(), $roles, true);
    }
}


// ══════════════════════════════════════════════════════════════════════════════
// AUTH API — issues/refreshes JWT tokens
// ══════════════════════════════════════════════════════════════════════════════

class AuthApiController extends BaseApiController
{
    public function login(): ResponseInterface
    {
        $email    = $this->request->getJSON(true)['email'] ?? $this->request->getPost('email');
        $password = $this->request->getJSON(true)['password'] ?? $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            return $this->error('Email and password are required.');
        }

        $userModel = new \App\Models\UserModel();
        $user      = $userModel->findByEmail($email);

        if (! $user || ! $userModel->verifyPassword($password, $user['password_hash'])) {
            return $this->unauthorized('Invalid email or password.');
        }

        if ($user['status'] !== 'active') {
            $message = match ($user['status']) {
                'pending'  => 'Your account is pending approval.',
                'rejected' => 'Your account application was not approved.',
                default    => 'Your account is inactive.',
            };
            return $this->unauthorized($message);
        }

        $jwt          = new \App\Libraries\JwtLibrary();
        $accessToken  = $jwt->encode($user);
        $refreshToken = $jwt->encodeRefresh($user['id']);

        // Store refresh token hash in DB
        $userModel->update($user['id'], [
            'refresh_token' => hash('sha256', $refreshToken),
            'last_login_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->ok([
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type'    => 'Bearer',
            'expires_in'    => $jwt->getAccessTtl(),
            'user' => [
                'id'         => $user['id'],
                'role'       => $user['role'],
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'email'      => $user['email'],
            ],
        ], 'Login successful');
    }

    public function refresh(): ResponseInterface
    {
        $body         = $this->request->getJSON(true) ?? [];
        $refreshToken = $body['refresh_token'] ?? null;

        if (! $refreshToken) {
            return $this->error('Refresh token is required.');
        }

        $userModel = new \App\Models\UserModel();

        // Find user by hashed refresh token
        $user = $userModel->where('refresh_token', hash('sha256', $refreshToken))
                          ->where('status', 'active')
                          ->first();

        if (! $user) {
            return $this->unauthorized('Invalid or expired refresh token.');
        }

        // Verify the token is actually a refresh token (not reusing access tokens)
        try {
            $jwt     = new \App\Libraries\JwtLibrary();
            $payload = $jwt->decode($refreshToken);

            if (($payload->type ?? '') !== 'refresh') {
                return $this->unauthorized('Invalid token type.');
            }
        } catch (\Exception $e) {
            return $this->unauthorized('Refresh token has expired. Please log in again.');
        }

        // Issue new tokens (rotation — old refresh token is replaced)
        $jwt             = new \App\Libraries\JwtLibrary();
        $newAccessToken  = $jwt->encode($user);
        $newRefreshToken = $jwt->encodeRefresh($user['id']);

        $userModel->update($user['id'], [
            'refresh_token' => hash('sha256', $newRefreshToken),
        ]);

        return $this->ok([
            'access_token'  => $newAccessToken,
            'refresh_token' => $newRefreshToken,
            'token_type'    => 'Bearer',
            'expires_in'    => $jwt->getAccessTtl(),
        ]);
    }

    public function logout(): ResponseInterface
    {
        // Invalidate refresh token
        $userId    = $this->authUserId();
        $userModel = new \App\Models\UserModel();
        $userModel->update($userId, ['refresh_token' => null]);

        return $this->ok(null, 'Logged out successfully.');
    }

    public function register(): ResponseInterface
    {
        // Delegates to AuthController logic but returns JSON
        // For a real mobile registration, this would mirror AuthController::doRegister()
        // but return JSON instead of redirects — implement when building the mobile app

        return $this->error('Mobile registration not yet implemented. Please register via the website.', 501);
    }

    public function checkStatus(): ResponseInterface
    {
        $email = $this->request->getJSON(true)['email'] ?? $this->request->getPost('email');

        if (! $email) {
            return $this->error('Email is required.');
        }

        $applications = new \App\Models\MemberApplicationModel();
        $application  = $applications->findByEmail($email);

        if (! $application) {
            return $this->notFound('No application found for this email address.');
        }

        return $this->ok([
            'status'     => $application['status'],
            'submitted'  => $application['created_at'],
            'reviewed'   => $application['reviewed_at'],
        ]);
    }
}
