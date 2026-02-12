<?php

namespace App\Controllers;

use App\Models\StoryModel;
use App\Models\ChapterModel;
use App\Models\ReviewModel;
use App\Models\RatingModel;
use App\Models\UserLibraryModel;
use App\Models\UserModel;

class StoryController extends BaseController
{
    protected $storyModel;
    protected $chapterModel;
    protected $reviewModel;
    protected $ratingModel;
    protected $libraryModel;
    protected $userModel;

    public function __construct()
    {
        $this->storyModel = new StoryModel();
        $this->chapterModel = new ChapterModel();
        $this->reviewModel = new ReviewModel();
        $this->ratingModel = new RatingModel();
        $this->libraryModel = new UserLibraryModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display story detail page
     */
    public function detail($id)
    {
        $story = $this->storyModel->getStoryWithDetails($id);

        if (!$story) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Story not found');
        }

        $data = [
            'title' => $story['title'],
            'story' => $story,
            'chapters' => $this->chapterModel->getChaptersByStory($id),
            'reviews' => $this->reviewModel->getReviewsByStory($id, 10),
            'avg_rating' => $this->ratingModel->getAverageRating($id),
            'rating_distribution' => $this->ratingModel->getRatingDistribution($id),
            'total_ratings' => $this->ratingModel->getTotalRatings($id),
        ];

        // Check if user has bookmarked
        if (session()->get('isLoggedIn')) {
            $userId = session()->get('user_id');
            $data['is_bookmarked'] = $this->libraryModel->isInLibrary($userId, $id);
            $data['user_rating'] = $this->ratingModel->getUserRating($userId, $id);
            $data['user_progress'] = $this->libraryModel->getProgress($userId, $id);
        }

        return view('story/detail', $data);
    }

    /**
     * Browse/discover stories
     */
    public function discover()
    {
        $genre = $this->request->getGet('genre');
        $search = $this->request->getGet('q');

        if ($search) {
            $stories = $this->storyModel->searchStories($search);
        } elseif ($genre) {
            $stories = $this->storyModel->getStoriesByGenre($genre);
        } else {
            $stories = $this->storyModel->getPublishedStories();
        }

        // Fetch trending stories by specific genres
        $trending_genres = ['Romance', 'Mystery', 'Fantasy'];
        $trending_data = [];
        foreach ($trending_genres as $genre_name) {
            $trending_data[$genre_name] = $this->storyModel->getStoriesByGenre($genre_name, 3);
        }

        // Fetch latest releases
        $latest_releases = $this->storyModel->getLatestReleases(3);

        // Fetch popular authors
        $popular_authors = $this->userModel->getPopularAuthors(6);

        $data = [
            'title' => 'Discover Stories',
            'stories' => $stories,
            'current_genre' => $genre,
            'search_query' => $search,
            'trending_genres' => $trending_genres,
            'trending_data' => $trending_data,
            'latest_releases' => $latest_releases,
            'popular_authors' => $popular_authors,
        ];

        return view('story/discover', $data);
    }

    /**
     * Add story to library
     */
    public function addToLibrary($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');
        
        if ($this->libraryModel->addToLibrary($userId, $id)) {
            return redirect()->back()->with('success', 'Cerita berhasil ditambahkan ke library');
        }

        return redirect()->back()->with('error', 'Cerita sudah ada di library Anda');
    }

    /**
     * Remove story from library
     */
    public function removeFromLibrary($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        
        if ($this->libraryModel->removeFromLibrary($userId, $id)) {
            return redirect()->back()->with('success', 'Cerita dihapus dari library');
        }

        return redirect()->back()->with('error', 'Gagal menghapus cerita dari library');
    }

    /**
     * Rate a story
     */
    public function rate($id)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Login required']);
        }

        $rating = $this->request->getPost('rating');
        $userId = session()->get('user_id');

        if ($this->ratingModel->addOrUpdateRating($userId, $id, $rating)) {
            $newAvg = $this->ratingModel->getAverageRating($id);
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Rating berhasil disimpan',
                'avg_rating' => $newAvg
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal menyimpan rating']);
    }
}
