<?php

namespace App\Controllers;

use App\Models\StoryModel;
use App\Models\ReviewModel;
use App\Models\UserModel;

class Home extends BaseController
{
    protected $storyModel;
    protected $reviewModel;
    protected $userModel;

    public function __construct()
    {
        $this->storyModel = new StoryModel();
        $this->reviewModel = new ReviewModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'CeritaKu - Platform Novel Indonesia',
            'featured_reviews' => $this->reviewModel->getFeaturedReviews(8),
            'latest_reviews' => $this->reviewModel->getLatestReviews(4),
            'total_stories' => $this->storyModel->getTotalStories('PUBLISHED'),
            'total_users' => $this->userModel->getTotalUsers(),
            'total_reviews' => $this->reviewModel->getTotalReviews(),
        ];

        return view('pages/home', $data);
    }
}
