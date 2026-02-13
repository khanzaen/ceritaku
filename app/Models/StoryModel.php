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
                ->orLike('users.name', $keyword)
            ->groupEnd()
            ->groupBy('stories.id')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get stories by genre
     */
    public function getStoriesByGenre(string $genreName, int $limit = null)
    {
        $builder = $this->select('stories.*, users.name as author_name, AVG(ratings.rating) as avg_rating, COUNT(DISTINCT ratings.id) as total_ratings, COUNT(DISTINCT user_library.id) as total_views')
            ->join('users', 'users.id = stories.author_id')
            ->join('ratings', 'ratings.story_id = stories.id', 'left')
            ->join('user_library', 'user_library.story_id = stories.id', 'left')
            ->like('stories.genres', $genreName)
            ->where('stories.status', 'PUBLISHED')
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

    /**
     * Get top stories this week based on rating and views
     * Ranking criteria: views minggu ini (primary) + rating (secondary)
     */
    public function getTopStoriesThisWeek(int $limit = 10)
    {
        $oneWeekAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        
        return $this->select('stories.*, 
                users.name as author_name,
                users.profile_photo as author_photo,
                AVG(ratings.rating) as avg_rating,
                COUNT(DISTINCT ratings.id) as total_ratings,
                COUNT(DISTINCT user_library.id) as total_views,
                SUM(CASE WHEN user_library.added_at >= \'' . $oneWeekAgo . '\' THEN 1 ELSE 0 END) as weekly_views')
            ->join('users', 'users.id = stories.author_id')
            ->join('ratings', 'ratings.story_id = stories.id', 'left')
            ->join('user_library', 'user_library.story_id = stories.id', 'left')
            ->where('stories.status', 'PUBLISHED')
            ->groupBy('stories.id')
            ->orderBy('weekly_views', 'DESC')
            ->orderBy('avg_rating', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get genres untuk story tertentu
     */
    public function getStoryGenres(int $storyId)
    {
        return $this->db->table('story_genres')
            ->select('genres.*')
            ->join('genres', 'genres.id = story_genres.genre_id')
            ->where('story_genres.story_id', $storyId)
            ->get()
            ->getResultArray();
    }

    /**
     * Set genres untuk story (replace existing)
     */
    public function setStoryGenres(int $storyId, array $genreIds)
    {
        // Hapus genres lama
        $this->db->table('story_genres')->where('story_id', $storyId)->delete();
        
        // Insert genres baru
        $data = [];
        foreach ($genreIds as $genreId) {
            $data[] = [
                'story_id' => $storyId,
                'genre_id' => $genreId,
            ];
        }
        
        if (!empty($data)) {
            $this->db->table('story_genres')->insertBatch($data);
        }
        
        return true;
    }

    /**
     * Add single genre ke story
     */
    public function addGenre(int $storyId, int $genreId)
    {
        // Check apakah sudah ada
        $existing = $this->db->table('story_genres')
            ->where('story_id', $storyId)
            ->where('genre_id', $genreId)
            ->countAllResults();
        
        if ($existing === 0) {
            return $this->db->table('story_genres')->insert([
                'story_id' => $storyId,
                'genre_id' => $genreId,
            ]);
        }
        
        return true;
    }

    /**
     * Remove genre dari story
     */
    public function removeGenre(int $storyId, int $genreId)
    {
        return $this->db->table('story_genres')
            ->where('story_id', $storyId)
            ->where('genre_id', $genreId)
            ->delete();
    }

    /**
     * Get stories by genre (dari junction table)
     */
    public function getStoriesByGenreId(int $genreId, int $limit = null)
    {
        $builder = $this->select('stories.*, users.name as author_name, AVG(ratings.rating) as avg_rating, COUNT(DISTINCT ratings.id) as total_ratings, COUNT(DISTINCT user_library.id) as total_views')
            ->join('users', 'users.id = stories.author_id')
            ->join('ratings', 'ratings.story_id = stories.id', 'left')
            ->join('user_library', 'user_library.story_id = stories.id', 'left')
            ->join('story_genres', 'story_genres.story_id = stories.id')
            ->where('story_genres.genre_id', $genreId)
            ->where('stories.status', 'PUBLISHED')
            ->groupBy('stories.id')
            ->orderBy('stories.created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }
}
