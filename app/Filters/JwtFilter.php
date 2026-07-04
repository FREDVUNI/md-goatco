<?php
declare(strict_types=1);
namespace App\Filters;
use App\Libraries\JwtLibrary;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class JwtFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null): mixed
    {
        $header = $request->getHeaderLine('Authorization');
        if (! $header || ! str_starts_with($header, 'Bearer ')) {
            return service('response')->setStatusCode(401)->setJSON(['error'=>'No token provided']);
        }
        $token   = substr($header, 7);
        $jwt     = new JwtLibrary();
        $payload = $jwt->verify($token);
        if (! $payload) {
            return service('response')->setStatusCode(401)->setJSON(['error'=>'Invalid or expired token']);
        }
        // Store user info for the controller
        $request->jwtPayload = $payload;
        return null;
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): mixed { return null; }
}
