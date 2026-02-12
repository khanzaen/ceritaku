<?php

namespace App\Models;

use CodeIgniter\Model;

class StoryModel extends Model
{
    protected $table            = 'stories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'author_id',
        'genres',
        'description',
        'cover_image',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'title'       => 'required|min_length[3]|max_length[150]',
        'author_id'   => 'required|integer',
        'genres'      => 'required|max_length[50]',
        'description' => 'permit_empty',
        'status'      => 'in_list[DRAFT,PENDING_REVIEW,PUBLISHED,ARCHIVED]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    /**
     * Get published stories with author info
     */
    public function getPublishedStories(int $limit = null)
    {
        $builder = $this->select('stories.*, users.name as author_name, users.profile_photo as author_photo, AVG(ratings.rating) as avg_rating, COUNT(DISTINCT ratings.id) as total_ratings, COUNT(DISTINCT user_library.id) as total_views')
            ->join('users', 'users.id = stories.author_id')
            ->join('ratings', 'ratings.story_id = stories.id', 'left')
            ->join('user_library', 'user_library.story_id = stories.id', 'left')
            ->where('stories.status', 'PUBLISHED')
            ->groupBy('stories.id')
            ->orderBy('stories.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get story with author and rating info
     */
    public function getStoryWithDetails(int $storyId)
    {
        return $this->select('stories.*, 
                users.name as author_name, 
                users.profile_photo as author_photo,
                users.bio as author_bio,
                AVG(ratings.rating) as avg_rating,
                COUNT(DISTINCT ratings.id) as total_ratings,
                COUNT(DISTINCT reviews.id) as total_reviews,
                COUNT(DISTINCT user_library.id) as total_views')
            ->join('users', 'users.id = stories.author_id')
            ->join('ratings', 'ratings.story_id = stories.id', 'left')
            ->join('reviews', 'reviews.story_id = stories.id', 'left')
            ->join('user_library', 'user_library.story_id = stories.id', 'left')
            ->where('stories.id', $storyId)
            ->groupBy('stories.id')
            ->first();
    }

    /**
     * Get stories by author
     */
    public function getStoriesByAuthor(int $authorId, string $status = null)
    {
        $builder = $this->where('author_id', $authorId);

        if ($status) {
            $builder->where('status', $status);
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Get total stories count
     */
    public function getTotalStories(string $status = 'PUBLISHED'): int
    {
        return $this->where('status', $status)->countAllResults();
    }

    /**
     * Search stories by title or genre
     */
    public function searchStories(string $keyword, int $limit = 10)
    {
        return $this->select('stories.*, users.name as author_name, AVG(ratings.rating) as avg_rating, COUNT(DISTINCT ratings.id) as total_ratings, COUNT(DISTINCT user_library.id) as total_views')
            ->join('users', 'users.id = stories.author_id')
            ->join('ratings', 'ratings.story_id = stories.id', 'left')
            ->join('user_library', 'user_library.story_id = stories.id', 'left')
            ->where('stories.status', 'PUBLISHED')
            ->groupStart()
                ->like('stories.title', $keyword)
                ->orLike('stories.genres', $keyword)
                ->orLike('users.name', $keyword)
            ->groupEnd()
            ->groupBy('stories.id')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get stories by genre
     */
    public function getStoriesByGenre(string $genre, int $limit = null)
    {
        $builder = $this->select('stories.*, users.name as author_name, AVG(ratings.rating) as avg_rating, COUNT(DISTINCT ratings.id) as total_ratings, COUNT(DISTINCT user_library.id) as total_views')
            ->join('users', 'users.id = stories.author_id')
            ->join('ratings', 'ratings.story_id = stories.id', 'left')
            ->join('user_library', 'user_library.story_id = stories.id', 'left')
            ->where('stories.status', 'PUBLISHED')
            ->like('stories.genres', $genre)
            ->groupBy('stories.id')
            ->orderBy('stories.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get featured stories (dengan rating tertinggi)
     */
    public function getFeaturedStories(int $limit = 6)
    {
        return $this->select('stories.*, 
                users.name as author_name,
                AVG(ratings.rating) as avg_rating,
                COUNT(DISTINCT ratings.id) as total_ratings,
                COUNT(DISTINCT reviews.id) as review_count,
                COUNT(DISTINCT user_library.id) as total_views')
            ->join('users', 'users.id = stories.author_id')
            ->join('ratings', 'ratings.story_id = stories.id', 'left')
            ->join('reviews', 'reviews.story_id = stories.id', 'left')
            ->join('user_library', 'user_library.story_id = stories.id', 'left')
            ->where('stories.status', 'PUBLISHED')
            ->groupBy('stories.id')
            ->orderBy('avg_rating', 'DESC')
            ->orderBy('review_count', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get latest releases (cerita terbaru)
     */
    public function getLatestReleases(int $limit = 3)
    {
        return $this->select('stories.*, 
                users.name as author_name,
                AVG(ratings.rating) as avg_rating,
                COUNT(DISTINCT ratings.id) as total_ratings,
                COUNT(DISTINCT user_library.id) as total_views')
            ->join('users', 'users.id = stories.author_id')
            ->join('ratings', 'ratings.story_id = stories.id', 'left')
            ->join('user_library', 'user_library.story_id = stories.id', 'left')
            ->where('stories.status', 'PUBLISHED')
            ->groupBy('stories.id')
            ->orderBy('stories.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
