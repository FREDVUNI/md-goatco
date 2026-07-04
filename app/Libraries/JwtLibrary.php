<?php
declare(strict_types=1);
namespace App\Libraries;

class JwtLibrary
{
    private string $secret;
    private int    $ttl;
    private int    $refreshTtl;

    public function __construct()
    {
        $this->secret     = env('JWT_SECRET', 'change-this-secret-key');
        $this->ttl        = (int) env('JWT_TTL', 3600);
        $this->refreshTtl = (int) env('JWT_REFRESH_TTL', 604800);
    }

    public function generate(array $payload): string
    {
        $header  = $this->base64UrlEncode(json_encode(['alg'=>'HS256','typ'=>'JWT']));
        $payload = $this->base64UrlEncode(json_encode(array_merge($payload, [
            'iat' => time(),
            'exp' => time() + $this->ttl,
        ])));
        $sig = $this->base64UrlEncode(hash_hmac('sha256', "$header.$payload", $this->secret, true));
        return "$header.$payload.$sig";
    }

    public function verify(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;
        [$header, $payload, $sig] = $parts;
        $expected = $this->base64UrlEncode(hash_hmac('sha256', "$header.$payload", $this->secret, true));
        if (! hash_equals($expected, $sig)) return null;
        $data = json_decode($this->base64UrlDecode($payload), true);
        if (! $data || ($data['exp'] ?? 0) < time()) return null;
        return $data;
    }

    private function base64UrlEncode(string $data): string { return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); }
    private function base64UrlDecode(string $data): string { return base64_decode(strtr($data, '-_', '+/')); }
}
