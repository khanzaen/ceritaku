<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserManagement extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Check admin access — uses correct session key 'user_role'
     */
    private function checkAdmin()
    {
        if (!session()->get('isLoggedIn') || strtoupper(session()->get('user_role')) !== 'ADMIN') {
            return redirect()->to('/')->with('error', 'Akses ditolak');
        }
        return null;
    }

    /**
     * List all users
     */
    public function index()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'User Management',
            'users' => $this->userModel->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('admin/user-management', $data);
    }

    /**
     * Verify user
     */
    public function verify($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->userModel->update($id, ['is_verified' => 1])) {
            return redirect()->back()->with('success', 'User berhasil diverifikasi');
        }
        return redirect()->back()->with('error', 'Gagal memverifikasi user');
    }

    /**
     * Change user role
     */
    public function changeRole($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $role = $this->request->getPost('role');

        if ($this->userModel->update($id, ['role' => $role])) {
            return redirect()->back()->with('success', 'Role user berhasil diubah');
        }
        return redirect()->back()->with('error', 'Gagal mengubah role user');
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($id == session()->get('user_id')) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->back()->with('success', 'User berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Gagal menghapus user');
    }
}
