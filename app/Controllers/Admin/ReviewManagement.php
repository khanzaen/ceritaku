<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReviewModel;

class ReviewManagement extends BaseController
{
    protected $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
    }

    private function checkAdmin()
    {
        if (!session()->get('isLoggedIn') || strtoupper(session()->get('user_role')) !== 'ADMIN') {
            return redirect()->to('/')->with('error', 'Akses ditolak');
        }
        return null;
    }

    public function index()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title'   => 'Review Management',
            'reviews' => $this->reviewModel
                ->select('reviews.*, users.name as user_name, stories.title as story_title')
                ->join('users', 'users.id = reviews.user_id', 'left')
                ->join('stories', 'stories.id = reviews.story_id', 'left')
                ->orderBy('reviews.created_at', 'DESC')
                ->findAll(),
        ];

        return view('admin/review-management', $data);
    }

    public function delete($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->reviewModel->delete($id)) {
            return redirect()->back()->with('success', 'Review berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Gagal menghapus review');
    }
}
