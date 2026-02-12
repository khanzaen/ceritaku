<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table            = 'reviews';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'story_id',
        'user_id',
        'review',
        'is_featured'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'story_id'    => 'required|integer',
        'user_id'     => 'required|integer',
        'review'      => 'required|min_length[10]',
        'is_featured' => 'in_list[0,1]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    /**
     * Get reviews by story with user info
     */
    public function getReviewsByStory(int $storyId, int $limit = null)
    {
        $builder = $this->select('reviews.*, 
                users.name as user_name,
                users.profile_photo as user_photo,
                COUNT(review_likes.id) as likes_count')
            ->join('users', 'users.id = reviews.user_id')
            ->join('review_likes', 'review_likes.review_id = reviews.id', 'left')
            ->where('reviews.story_id', $storyId)
            ->groupBy('reviews.id')
            ->orderBy('likes_count', 'DESC')
            ->orderBy('reviews.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get featured reviews untuk homepage (1 review terpopuler per story)
     */
    public function getFeaturedReviews(int $limit = 6)
    {
        $db = \Config\Database::connect();
        
        // Subquery: Dapatkan review_id dengan likes terbanyak per story
        $sql = "
            SELECT 
                reviews.*,
                stories.id as story_id,
                stories.title as story_title,
                stories.cover_image,
                stories.genres,
                users.name as user_name,
                users.profile_photo as user_photo,
                author.name as author_name,
                COALESCE(likes.likes_count, 0) as likes_count
            FROM reviews
            INNER JOIN (
                SELECT 
                    r.story_id,
                    MAX(r.id) as review_id,
                    MAX(like_counts.likes_count) as max_likes
                FROM reviews r
                INNER JOIN stories s ON s.id = r.story_id
                LEFT JOIN (
                    SELECT review_id, COUNT(*) as likes_count
                    FROM review_likes
                    GROUP BY review_id
                ) as like_counts ON like_counts.review_id = r.id
                WHERE s.status = 'PUBLISHED'
                GROUP BY r.story_id
                ORDER BY max_likes DESC, MAX(r.created_at) DESC
                LIMIT ?
            ) as top_reviews ON top_reviews.review_id = reviews.id
            INNER JOIN stories ON stories.id = reviews.story_id
            INNER JOIN users ON users.id = reviews.user_id
            INNER JOIN users as author ON author.id = stories.author_id
            LEFT JOIN (
                SELECT review_id, COUNT(*) as likes_count
                FROM review_likes
                GROUP BY review_id
            ) as likes ON likes.review_id = reviews.id
            ORDER BY likes_count DESC, reviews.created_at DESC
        ";
        
        $query = $db->query($sql, [$limit]);
        return $query->getResultArray();
    }

    /**
     * Get latest reviews
     */
    public function getLatestReviews(int $limit = 10)
    {
        return $this->select('reviews.*, 
                stories.title as story_title,
                users.name as user_name,
                users.profile_photo as user_photo,
                COUNT(review_likes.id) as likes_count')
            ->join('stories', 'stories.id = reviews.story_id')
            ->join('users', 'users.id = reviews.user_id')
            ->join('review_likes', 'review_likes.review_id = reviews.id', 'left')
            ->where('stories.status', 'PUBLISHED')
            ->groupBy('reviews.id')
            ->orderBy('reviews.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get total reviews count
     */
    public function getTotalReviews(): int
    {
        return $this->countAll();
    }

    /**
     * Check if user already reviewed a story
     */
    public function hasUserReviewed(int $userId, int $storyId): bool
    {
        return $this->where('user_id', $userId)
            ->where('story_id', $storyId)
            ->countAllResults() > 0;
    }

    /**
     * Get user's review for a story
     */
    public function getUserReview(int $userId, int $storyId)
    {
        return $this->where('user_id', $userId)
            ->where('story_id', $storyId)
            ->first();
    }
}
