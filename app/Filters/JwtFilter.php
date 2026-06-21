<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\JwtLibrary;

/**
 * JwtFilter
 *
 * Applied to all protected API routes.
 * Validates the Bearer token, decodes the payload, and stores
 * the authenticated user in a request property so controllers
 * can access it via $request->authUser.
 */
class JwtFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null): mixed
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (empty($authHeader) || ! str_starts_with($authHeader, 'Bearer ')) {
            return $this->unauthorized('Missing or invalid Authorization header.');
        }

        $token = substr($authHeader, 7);

        try {
            $jwt     = new JwtLibrary();
            $payload = $jwt->decode($token);

            // Attach decoded payload to request for controllers to use
            $request->authUser = $payload;

        } catch (\Exception $e) {
            return $this->unauthorized($e->getMessage());
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): mixed
    {
        return null;
    }

    private function unauthorized(string $message): ResponseInterface
    {
        return service('response')
            ->setStatusCode(401)
            ->setContentType('application/json')
            ->setBody(json_encode([
                'status'  => 'error',
                'code'    => 401,
                'message' => $message,
            ]));
    }
}
