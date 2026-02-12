<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewLikeModel extends Model
{
    protected $table            = 'review_likes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'review_id',
        'user_id'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    // Validation
    protected $validationRules      = [
        'review_id' => 'required|integer',
        'user_id'   => 'required|integer'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    /**
     * Check if user has liked a review
     */
    public function hasUserLiked(int $userId, int $reviewId): bool
    {
        return $this->where('user_id', $userId)
            ->where('review_id', $reviewId)
            ->countAllResults() > 0;
    }

    /**
     * Toggle like (like or unlike)
     */
    public function toggleLike(int $userId, int $reviewId): bool
    {
        $existing = $this->where('user_id', $userId)
            ->where('review_id', $reviewId)
            ->first();

        if ($existing) {
            // Unlike - hapus like
            return $this->delete($existing['id']);
        } else {
            // Like - tambah like
            return $this->insert([
                'user_id'   => $userId,
                'review_id' => $reviewId
            ]) !== false;
        }
    }

    /**
     * Get total likes for a review
     */
    public function getLikesCount(int $reviewId): int
    {
        return $this->where('review_id', $reviewId)->countAllResults();
    }

    /**
     * Get users who liked a review
     */
    public function getUsersWhoLiked(int $reviewId)
    {
        return $this->select('users.*')
            ->join('users', 'users.id = review_likes.user_id')
            ->where('review_likes.review_id', $reviewId)
            ->findAll();
    }
}
