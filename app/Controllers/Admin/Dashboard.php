<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StoryModel;
use App\Models\UserModel;
use App\Models\ReviewModel;
use App\Models\ChapterModel;

class Dashboard extends BaseController
{
    protected $storyModel;
    protected $userModel;
    protected $reviewModel;
    protected $chapterModel;

    public function __construct()
    {
        $this->storyModel = new StoryModel();
        $this->userModel = new UserModel();
        $this->reviewModel = new ReviewModel();
        $this->chapterModel = new ChapterModel();
    }

    public function index()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'ADMIN') {
            return redirect()->to('/login')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
        }

        $data = [
            'title' => 'Admin Dashboard',
            'total_users' => $this->userModel->countAll(),
            'total_stories' => $this->storyModel->countAll(),
            'total_published' => $this->storyModel->getTotalStories('PUBLISHED'),
            'total_pending' => $this->storyModel->getTotalStories('PENDING_REVIEW'),
            'total_reviews' => $this->reviewModel->getTotalReviews(),
            'latest_stories' => $this->storyModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
            'latest_users' => $this->userModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
        ];

        return view('admin/dashboard', $data);
    }
}
