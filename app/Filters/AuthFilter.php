<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Kalau belum login, redirect ke home
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Kalau route admin, cek apakah rolenya ADMIN
        if ($arguments && in_array('admin', $arguments)) {
            if (strtoupper(session()->get('user_role')) !== 'ADMIN') {
                return redirect()->to('/')->with('error', 'Akses ditolak. Hanya admin.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak perlu apa-apa
    }
}