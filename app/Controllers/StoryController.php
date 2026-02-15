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

        // Get primary genre for related stories
        $genres = array_filter(array_map('trim', explode(',', $story['genres'] ?? '')));
        $primary_genre = !empty($genres) ? $genres[0] : '';

        $data = [
            'title' => $story['title'],
            'story' => $story,
            // Perbaiki: hanya kirim story_id, biar status default 'PUBLISHED' digunakan
            'chapters' => $this->chapterModel->getChaptersByStory($id),
            'reviews' => $this->reviewModel->getReviewsByStory($id, 3),
            'related_stories' => !empty($primary_genre) ? $this->storyModel->getStoriesByGenre($primary_genre, 6) : [],
            'is_bookmarked' => false,
        ];

        // Check if user has bookmarked
        if (session()->get('isLoggedIn')) {
            $userId = session()->get('user_id');
            $data['is_bookmarked'] = $this->libraryModel->isInLibrary($userId, $id);
            $data['user_rating'] = $this->ratingModel->getUserRating($userId, $id);
            $data['user_progress'] = $this->libraryModel->getProgress($userId, $id);
        }

        return view('pages/story-detail', $data);
    }

    /**
     * Browse/discover stories
     */
    public function discover()
    {
        $genre = $this->request->getGet('genre');
        $search = $this->request->getGet('q');

        // Get top picks this week for main display
        $stories = $this->storyModel->getTopStoriesThisWeek(10);

        // If there's a search or genre filter, override with specific results
        if ($search) {
            $stories = $this->storyModel->searchStories($search);
        } elseif ($genre) {
            $stories = $this->storyModel->getStoriesByGenre($genre);
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

        return view('pages/discover', $data);
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
