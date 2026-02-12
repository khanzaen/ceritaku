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
     * Check admin access
     */
    private function checkAdmin()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'ADMIN') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
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
            'title' => 'Manage Users',
            'users' => $this->userModel->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('admin/users/index', $data);
    }

    /**
     * Verify user
     */
    public function verify($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->userModel->verifyUser($id)) {
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

        // Prevent deleting own account
        if ($id == session()->get('user_id')) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->back()->with('success', 'User berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Gagal menghapus user');
    }
}
