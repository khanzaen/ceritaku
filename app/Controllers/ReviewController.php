<?php

namespace App\Controllers;

use App\Models\ReviewModel;
use App\Models\StoryModel;

class ReviewController extends BaseController
{
    protected $reviewModel;
    protected $storyModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
        $this->storyModel  = new StoryModel();
    }

    /**
     * Halaman my reviews milik user yang sedang login
     */
    public function myReviews()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');

        $reviews = $this->reviewModel
            ->select('reviews.*, stories.title as story_title, stories.cover_image, stories.id as story_id, users.name as author_name, COUNT(review_likes.id) as likes_count')
            ->join('stories', 'stories.id = reviews.story_id')
            ->join('users', 'users.id = stories.author_id', 'left')
            ->join('review_likes', 'review_likes.review_id = reviews.id', 'left')
            ->where('reviews.user_id', $userId)
            ->groupBy('reviews.id')
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll();

        $data = [
            'title'   => 'My Reviews',
            'reviews' => $reviews,
        ];

        return view('pages/user/my-reviews', $data);
    }

    /**
     * Submit review
     */
    public function submit($storyId)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please login first']);
        }

        $userId = session()->get('user_id');

        if ($this->reviewModel->hasUserReviewed($userId, $storyId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'You have already reviewed this story']);
        }

        $review = $this->request->getPost('review');
        $rating = (int) $this->request->getPost('rating');

        if (empty($review) || strlen($review) < 10) {
            return $this->response->setJSON(['success' => false, 'message' => 'Review must be at least 10 characters']);
        }

        if ($rating < 1 || $rating > 5) {
            return $this->response->setJSON(['success' => false, 'message' => 'Rating must be between 1 and 5']);
        }

        $data = [
            'story_id' => $storyId,
            'user_id'  => $userId,
            'review'   => $review,
            'rating'   => $rating,
        ];

        if ($this->reviewModel->skipValidation(true)->insert($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Review submitted successfully']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to submit review']);
    }

    /**
     * Update review
     */
    public function update($reviewId)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please login first']);
        }

        $userId = session()->get('user_id');
        $review = $this->reviewModel->find($reviewId);

        if (!$review) {
            return $this->response->setJSON(['success' => false, 'message' => 'Review not found']);
        }

        if ($review['user_id'] != $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $reviewText = $this->request->getPost('review');
        $rating     = (int) $this->request->getPost('rating');

        if (empty($reviewText) || strlen($reviewText) < 10) {
            return $this->response->setJSON(['success' => false, 'message' => 'Review must be at least 10 characters']);
        }

        if ($rating < 1 || $rating > 5) {
            return $this->response->setJSON(['success' => false, 'message' => 'Rating must be between 1 and 5']);
        }

        if ($this->reviewModel->skipValidation(true)->update($reviewId, ['review' => $reviewText, 'rating' => $rating])) {
            return $this->response->setJSON(['success' => true, 'message' => 'Review updated successfully']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update review']);
    }

    /**
     * Delete review
     */
    public function delete($reviewId)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please login first']);
        }

        $userId = session()->get('user_id');
        $review = $this->reviewModel->find($reviewId);

        if (!$review) {
            return $this->response->setJSON(['success' => false, 'message' => 'Review not found']);
        }

        if ($review['user_id'] != $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        if ($this->reviewModel->delete($reviewId)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Review deleted']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete review']);
    }
}