<?php

declare(strict_types=1);

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * JwtLibrary
 *
 * Wraps firebase/php-jwt for use in the API layer.
 *
 * Config lives in .env:
 *   JWT_SECRET=your-super-secret-key-min-32-chars
 *   JWT_TTL=3600          (access token lifetime in seconds)
 *   JWT_REFRESH_TTL=604800 (refresh token lifetime — 7 days)
 */
class JwtLibrary
{
    private string $secret;
    private int    $ttl;
    private int    $refreshTtl;
    private string $algo = 'HS256';

    public function __construct()
    {
        $this->secret     = env('JWT_SECRET', 'changeme-use-a-proper-secret-in-env');
        $this->ttl        = (int) env('JWT_TTL', 3600);
        $this->refreshTtl = (int) env('JWT_REFRESH_TTL', 604800);
    }

    /**
     * Issue an access token for a user.
     */
    public function encode(array $user): string
    {
        $now = time();

        $payload = [
            'iss'  => base_url(),          // issuer
            'aud'  => 'mdgoatco-api',      // audience
            'iat'  => $now,                // issued at
            'nbf'  => $now,                // not before
            'exp'  => $now + $this->ttl,   // expiry
            // User claims
            'sub'  => (string) $user['id'],
            'role' => $user['role'],
            'name' => trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')),
            'email'=> $user['email'],
        ];

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    /**
     * Issue a longer-lived refresh token.
     */
    public function encodeRefresh(int $userId): string
    {
        $now = time();

        $payload = [
            'iss'  => base_url(),
            'aud'  => 'mdgoatco-api-refresh',
            'iat'  => $now,
            'exp'  => $now + $this->refreshTtl,
            'sub'  => (string) $userId,
            'type' => 'refresh',
        ];

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    /**
     * Decode and verify a token.
     * Throws \Exception on failure (expired, invalid signature, etc.)
     *
     * @throws \Firebase\JWT\ExpiredException
     * @throws \Firebase\JWT\SignatureInvalidException
     * @throws \UnexpectedValueException
     */
    public function decode(string $token): object
    {
        return JWT::decode($token, new Key($this->secret, $this->algo));
    }

    /**
     * Decode without verification — useful for reading an expired token's claims
     * before deciding whether to allow a refresh. Never trust the result for auth.
     */
    public function decodeUnverified(string $token): object
    {
        $parts   = explode('.', $token);
        $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), false);
        return $payload;
    }

    public function getAccessTtl(): int
    {
        return $this->ttl;
    }

    public function getRefreshTtl(): int
    {
        return $this->refreshTtl;
    }
}
