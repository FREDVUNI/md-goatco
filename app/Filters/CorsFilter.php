<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * CorsFilter
 *
 * Sets CORS headers on all /api/* responses so the mobile app
 * and PWA can call the API from any origin during development,
 * and from the production domain(s) in production.
 *
 * Adjust $allowedOrigins for production.
 */
class CorsFilter implements FilterInterface
{
    private array $allowedOrigins = [
        'http://localhost:3000',     // React Native / Expo dev
        'http://localhost:5173',     // Vite PWA dev
        'https://mdgoatco.farm',
        'https://www.mdgoatco.farm',
        'https://app.mdgoatco.farm',
    ];

    public function before(RequestInterface $request, $arguments = null): mixed
    {
        // Handle preflight OPTIONS request
        if ($request->getMethod() === 'options') {
            $response = service('response');
            $this->setHeaders($response, $request);
            return $response->setStatusCode(204);
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): mixed
    {
        $this->setHeaders($response, $request);
        return $response;
    }

    private function setHeaders(ResponseInterface $response, RequestInterface $request): void
    {
        $origin = $request->getHeaderLine('Origin');

        if (in_array($origin, $this->allowedOrigins, true)) {
            $response->setHeader('Access-Control-Allow-Origin', $origin);
        } else {
            $response->setHeader('Access-Control-Allow-Origin', 'null');
        }

        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        $response->setHeader('Access-Control-Allow-Credentials', 'true');
        $response->setHeader('Access-Control-Max-Age', '86400');
        $response->setHeader('Vary', 'Origin');
    }
}
