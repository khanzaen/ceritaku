<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReviewModel;

class ReviewManagementController extends BaseController
{
    protected $reviewModel;

    public function __construct()
    {
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
     * List all reviews with user & story info
     */
    public function index()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        $reviews = $db->table('reviews')
            ->select('
                reviews.*,
                users.name as user_name,
                users.profile_photo as user_photo,
                stories.title as story_title,
                stories.cover_image as story_cover,
                stories.id as story_id,
                COUNT(DISTINCT review_likes.id) as likes_count
            ')
            ->join('users', 'users.id = reviews.user_id', 'left')
            ->join('stories', 'stories.id = reviews.story_id', 'left')
            ->join('review_likes', 'review_likes.review_id = reviews.id', 'left')
            ->groupBy('reviews.id')
            ->orderBy('reviews.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'   => 'Review Management',
            'reviews' => $reviews,
        ];

        return view('admin/review/index', $data);
    }

    /**
     * Get review detail (JSON for slide-over panel)
     */
    public function detail($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        $review = $db->table('reviews')
            ->select('
                reviews.*,
                users.name as user_name,
                users.email as user_email,
                users.profile_photo as user_photo,
                stories.title as story_title,
                stories.cover_image as story_cover,
                stories.id as story_id,
                COUNT(DISTINCT review_likes.id) as likes_count
            ')
            ->join('users', 'users.id = reviews.user_id', 'left')
            ->join('stories', 'stories.id = reviews.story_id', 'left')
            ->join('review_likes', 'review_likes.review_id = reviews.id', 'left')
            ->where('reviews.id', $id)
            ->groupBy('reviews.id')
            ->get()
            ->getRowArray();

        if (!$review) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Review not found']);
        }

        return $this->response->setJSON($review);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $review = $this->reviewModel->find($id);
        if (!$review) return redirect()->back()->with('error', 'Review not found');

        $newFeatured = $review['is_featured'] ? 0 : 1;
        $this->reviewModel->update($id, ['is_featured' => $newFeatured]);

        $msg = $newFeatured ? 'Review ditandai sebagai featured.' : 'Review dihapus dari featured.';
        return redirect()->back()->with('success', $msg);
    }

    /**
     * Delete review
     */
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
