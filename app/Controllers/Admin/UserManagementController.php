<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\StoryModel;
use App\Models\ReviewModel;

class UserManagementController extends BaseController
{
    protected $userModel;
    protected $storyModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->userModel   = new UserModel();
        $this->storyModel  = new StoryModel();
        $this->reviewModel = new ReviewModel();
    }

    /**
     * Check admin access
     */
    private function checkAdmin()
    {
        if (!session()->get('isLoggedIn') || strtoupper(session()->get('user_role')) !== 'ADMIN') {
            return redirect()->to('/')->with('error', 'Akses ditolak');
        }
        return null;
    }

    /**
     * List all users with stats
     */
    public function index()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        $users = $db->table('users')
            ->select('
                users.*,
                COUNT(DISTINCT stories.id) as total_stories,
                COUNT(DISTINCT reviews.id) as total_reviews
            ')
            ->join('stories', 'stories.author_id = users.id', 'left')
            ->join('reviews', 'reviews.user_id = users.id', 'left')
            ->groupBy('users.id')
            ->orderBy('users.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'User Management',
            'users' => $users,
        ];

        return view('admin/user/index', $data);
    }

    /**
     * Get user detail (JSON for slide-over panel)
     */
    public function detail($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $db   = \Config\Database::connect();

        $user = $db->table('users')
            ->select('
                users.*,
                COUNT(DISTINCT stories.id) as total_stories,
                COUNT(DISTINCT reviews.id) as total_reviews,
                COUNT(DISTINCT user_library.id) as total_library
            ')
            ->join('stories', 'stories.author_id = users.id', 'left')
            ->join('reviews', 'reviews.user_id = users.id', 'left')
            ->join('user_library', 'user_library.user_id = users.id', 'left')
            ->where('users.id', $id)
            ->groupBy('users.id')
            ->get()
            ->getRowArray();

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
        }

        // Remove password from response
        unset($user['password']);

        // Get recent stories
        $user['recent_stories'] = $db->table('stories')
            ->select('id, title, status, created_at')
            ->where('author_id', $id)
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();

        return $this->response->setJSON($user);
    }

    /**
     * Update user role and verified status from slide-over
     */
    public function update($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $currentUserId = session()->get('user_id');
        if ($id == $currentUserId) {
            return redirect()->back()->with('error', 'Tidak dapat mengubah akun sendiri');
        }

        $role       = $this->request->getPost('role');
        $isVerified = $this->request->getPost('is_verified');

        $allowedRoles = ['USER', 'ADMIN'];
        if (!in_array($role, $allowedRoles)) {
            return redirect()->back()->with('error', 'Role tidak valid');
        }

        $updateData = ['role' => $role];

        if ($isVerified !== null) {
            $updateData['is_verified'] = (int) $isVerified;
        }

        if ($this->userModel->update($id, $updateData)) {
            return redirect()->to(base_url('/admin/users'))->with('success', 'User berhasil diperbarui.');
        }
        return redirect()->back()->with('error', 'Gagal memperbarui user.');
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

        $currentUserId = session()->get('user_id');
        if ($id == $currentUserId) {
            return redirect()->back()->with('error', 'Tidak dapat mengubah role akun sendiri');
        }

        $role = $this->request->getPost('role');

        $allowedRoles = ['USER', 'ADMIN'];
        if (!in_array($role, $allowedRoles)) {
            return redirect()->back()->with('error', 'Role tidak valid');
        }

        if ($this->userModel->update($id, ['role' => $role])) {
            return redirect()->back()->with('success', 'Role user berhasil diubah ke ' . $role);
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

        $currentUserId = session()->get('user_id');
        if ($id == $currentUserId) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->back()->with('success', 'User berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Gagal menghapus user');
    }
}
