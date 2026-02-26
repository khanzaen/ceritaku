<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

/**
 * API Auth Controller
 *
 * Endpoints:
 *   POST /api/auth/register  → daftar akun baru, return token
 *   POST /api/auth/login     → login, return token
 *   POST /api/auth/logout    → invalidate token (client-side)
 *   GET  /api/auth/me        → data user yang sedang login (butuh token)
 *   POST /api/auth/refresh   → refresh token yang hampir expired
 */
class AuthController extends ResourceController
{
    protected UserModel $userModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->userModel = new UserModel();
    }

    // ─────────────────────────────────────────────────────────────
    //  POST /api/auth/register
    // ─────────────────────────────────────────────────────────────
    public function register()
    {
        $json = $this->request->getJSON(true) ?? [];

        $name            = trim($json['name']             ?? '');
        $email           = trim($json['email']            ?? '');
        $password        = $json['password']              ?? '';
        $passwordConfirm = $json['password_confirm']      ?? '';

        // Validasi field wajib
        if (!$name || !$email || !$password || !$passwordConfirm) {
            return $this->fail('Semua field wajib diisi (name, email, password, password_confirm)', 422);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->fail('Format email tidak valid', 422);
        }

        if (strlen($password) < 8) {
            return $this->fail('Password minimal 8 karakter', 422);
        }

        if ($password !== $passwordConfirm) {
            return $this->fail('Konfirmasi password tidak cocok', 422);
        }

        // Cek email duplikat
        if ($this->userModel->where('email', $email)->first()) {
            return $this->fail('Email sudah terdaftar', 409);
        }

        // Buat user
        $user = $this->userModel->createUser([
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
            'role'     => 'USER',
        ]);

        if (!$user) {
            return $this->fail('Registrasi gagal, coba lagi', 500);
        }

        $token = $this->generateToken($user);

        return $this->respondCreated([
            'status'  => 'success',
            'message' => 'Registrasi berhasil',
            'data'    => [
                'token'      => $token,
                'token_type' => 'Bearer',
                'expires_in' => $this->tokenTTL(),
                'user'       => $this->formatUser($user),
            ],
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  POST /api/auth/login
    // ─────────────────────────────────────────────────────────────
    public function login()
    {
        $json = $this->request->getJSON(true) ?? [];

        $email    = trim($json['email']    ?? '');
        $password = $json['password']      ?? '';

        if (!$email || !$password) {
            return $this->fail('Email dan password wajib diisi', 422);
        }

        // Cek email
        if (!$this->userModel->where('email', $email)->first()) {
            return $this->failUnauthorized('Email tidak terdaftar');
        }

        // Verifikasi password
        $user = $this->userModel->authenticate($email, $password);
        if (!$user) {
            return $this->failUnauthorized('Password salah');
        }

        $token = $this->generateToken($user);

        return $this->respond([
            'status'  => 'success',
            'message' => 'Login berhasil',
            'data'    => [
                'token'      => $token,
                'token_type' => 'Bearer',
                'expires_in' => $this->tokenTTL(),
                'user'       => $this->formatUser($user),
            ],
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  POST /api/auth/logout
    // ─────────────────────────────────────────────────────────────
    /**
     * JWT adalah stateless — invalidasi dilakukan di sisi client
     * dengan menghapus token. Endpoint ini dikembalikan 200 sebagai
     * konfirmasi agar client tahu logout berhasil diproses.
     *
     * Jika ingin server-side blacklist, tambahkan tabel `token_blacklist`
     * dan cek di JwtFilter::before().
     */
    public function logout()
    {
        return $this->respond([
            'status'  => 'success',
            'message' => 'Logout berhasil. Hapus token di sisi client.',
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  GET /api/auth/me   (butuh Authorization: Bearer <token>)
    // ─────────────────────────────────────────────────────────────
    public function me()
    {
        $user = $this->getAuthUser();
        if (!$user) {
            return $this->failUnauthorized('Token tidak valid atau sudah expired');
        }

        return $this->respond([
            'status' => 'success',
            'data'   => ['user' => $this->formatUser($user)],
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  POST /api/auth/refresh   (butuh Authorization: Bearer <token>)
    // ─────────────────────────────────────────────────────────────
    public function refresh()
    {
        $payload = $this->decodeToken($this->getBearerToken());

        if (!$payload) {
            return $this->failUnauthorized('Token tidak valid');
        }

        $user = $this->userModel->find($payload['sub']);
        if (!$user) {
            return $this->failUnauthorized('User tidak ditemukan');
        }

        $newToken = $this->generateToken($user);

        return $this->respond([
            'status'  => 'success',
            'message' => 'Token berhasil diperbarui',
            'data'    => [
                'token'      => $newToken,
                'token_type' => 'Bearer',
                'expires_in' => $this->tokenTTL(),
            ],
        ]);
    }

    // ═════════════════════════════════════════════════════════════
    //  JWT HELPERS
    // ═════════════════════════════════════════════════════════════

    /**
     * TTL token dalam detik (default 7 hari).
     * Bisa di-override via .env: JWT_TTL=604800
     */
    private function tokenTTL(): int
    {
        return (int) (env('JWT_TTL') ?: 604800);
    }

    /**
     * Secret key untuk signing JWT.
     * Wajib diset di .env: JWT_SECRET=your-very-long-random-secret
     */
    private function jwtSecret(): string
    {
        $secret = env('JWT_SECRET');
        if (empty($secret)) {
            // fallback ke encryption key CI4 jika JWT_SECRET belum diset
            $secret = env('encryption.key') ?: 'default-insecure-key-change-me';
            log_message('warning', '[JWT] JWT_SECRET belum diset di .env!');
        }
        return $secret;
    }

    /**
     * Generate JWT token (header.payload.signature)
     */
    private function generateToken(array $user): string
    {
        $now = time();
        $exp = $now + $this->tokenTTL();

        $header = $this->base64UrlEncode(json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT',
        ]));

        $payload = $this->base64UrlEncode(json_encode([
            'sub'   => $user['id'],
            'email' => $user['email'],
            'role'  => $user['role'] ?? 'USER',
            'iat'   => $now,
            'exp'   => $exp,
        ]));

        $signature = $this->base64UrlEncode(
            hash_hmac('sha256', "$header.$payload", $this->jwtSecret(), true)
        );

        return "$header.$payload.$signature";
    }

    /**
     * Decode dan validasi JWT token.
     * Return payload array jika valid, null jika tidak.
     */
    private function decodeToken(?string $token): ?array
    {
        if (!$token) return null;

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

    /**
     * Ambil token dari header Authorization: Bearer <token>
     */
    private function getBearerToken(): ?string
    {
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (preg_match('/^Bearer\s+(.+)$/i', $authHeader, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Ambil user dari token Bearer yang sedang aktif
     */
    private function getAuthUser(): ?array
    {
        $payload = $this->decodeToken($this->getBearerToken());
        if (!$payload || empty($payload['sub'])) return null;

        return $this->userModel->find($payload['sub']) ?: null;
    }

    // ═════════════════════════════════════════════════════════════
    //  FORMAT HELPERS
    // ═════════════════════════════════════════════════════════════

    /**
     * Field user yang aman untuk dikembalikan ke API
     */
    private function formatUser(array $user): array
    {
        return [
            'id'            => $user['id'],
            'name'          => $user['name'],
            'username'      => $user['username'] ?? null,
            'email'         => $user['email'],
            'role'          => $user['role'] ?? 'USER',
            'bio'           => $user['bio'] ?? null,
            'profile_photo' => $user['profile_photo']
                                ? base_url('uploads/' . $user['profile_photo'])
                                : null,
            'is_verified'   => (bool) ($user['is_verified'] ?? false),
            'joined_at'     => $user['created_at'],
        ];
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