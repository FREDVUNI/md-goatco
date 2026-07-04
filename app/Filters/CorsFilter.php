<?php
declare(strict_types=1);
namespace App\Filters;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CorsFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null): mixed
    {
        if ($request->getMethod() === 'options') {
            $response = service('response');
            $response->setHeader('Access-Control-Allow-Origin',  '*')
                     ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                     ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                     ->setStatusCode(204);
            return $response;
        }
        return null;
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): mixed
    {
        return $response->setHeader('Access-Control-Allow-Origin','*');
    }
}
