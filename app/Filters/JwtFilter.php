<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * JwtFilter — Melindungi API route yang butuh autentikasi.
 *
 * Cara pakai di Routes.php:
 *   $routes->get('/api/auth/me', 'Api\AuthController::me', ['filter' => 'jwt']);
 *
 * Atau secara group:
 *   $routes->group('api', ['filter' => 'jwt'], function($routes) {
 *       ...
 *   });
 */
class JwtFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');

        // Ambil token dari header Authorization: Bearer <token>
        if (!preg_match('/^Bearer\s+(.+)$/i', $authHeader, $matches)) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Token tidak ditemukan. Sertakan header: Authorization: Bearer <token>',
                ]);
        }

        $token = $matches[1];

        $payload = $this->decodeToken($token);

        if (!$payload) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Token tidak valid atau sudah expired',
                ]);
        }

        // Simpan payload ke header agar bisa diakses controller
        // Cara alternatif: simpan ke $_SERVER atau session CI4
        $request->setGlobal('server', ['JWT_PAYLOAD' => json_encode($payload)]);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada post-processing
    }

    // ─── JWT Helpers (sama dengan AuthController, agar Filter standalone) ───

    private function tokenTTL(): int
    {
        return (int) (env('JWT_TTL') ?: 604800);
    }

    private function jwtSecret(): string
    {
        $secret = env('JWT_SECRET');
        if (empty($secret)) {
            $secret = env('encryption.key') ?: 'default-insecure-key-change-me';
        }
        return $secret;
    }

    private function decodeToken(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;

        [$header, $payload, $signature] = $parts;

        // Verifikasi signature
        $expected = $this->base64UrlEncode(
            hash_hmac('sha256', "$header.$payload", $this->jwtSecret(), true)
        );

        if (!hash_equals($expected, $signature)) return null;

        // Decode payload
        $data = json_decode($this->base64UrlDecode($payload), true);
        if (!$data) return null;

        // Cek expired
        if (isset($data['exp']) && $data['exp'] < time()) return null;

        return $data;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }
}