<?php

namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model
{
    protected $table            = 'ratings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'story_id',
        'user_id',
        'rating'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'story_id' => 'required|integer',
        'user_id'  => 'required|integer',
        'rating'   => 'required|integer|in_list[1,2,3,4,5]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    /**
     * Get average rating for a story
     */
    public function getAverageRating(int $storyId): float
    {
        $result = $this->selectAvg('rating', 'avg_rating')
            ->where('story_id', $storyId)
            ->first();

        return $result ? round((float)$result['avg_rating'], 1) : 0.0;
    }

    /**
     * Get user's rating for a story
     */
    public function getUserRating(int $userId, int $storyId)
    {
        return $this->where('user_id', $userId)
            ->where('story_id', $storyId)
            ->first();
    }

    /**
     * Add or update rating
     */
    public function addOrUpdateRating(int $userId, int $storyId, int $rating): bool
    {
        $existing = $this->getUserRating($userId, $storyId);

        if ($existing) {
            // Update existing rating
            return $this->update($existing['id'], ['rating' => $rating]);
        } else {
            // Insert new rating
            return $this->insert([
                'user_id'  => $userId,
                'story_id' => $storyId,
                'rating'   => $rating
            ]) !== false;
        }
    }

    /**
     * Get rating distribution for a story
     */
    public function getRatingDistribution(int $storyId): array
    {
        $result = $this->select('rating, COUNT(*) as count')
            ->where('story_id', $storyId)
            ->groupBy('rating')
            ->orderBy('rating', 'DESC')
            ->findAll();

        // Initialize distribution array
        $distribution = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0
        ];

        // Fill with actual counts
        foreach ($result as $row) {
            $distribution[$row['rating']] = (int)$row['count'];
        }

        return $distribution;
    }

    /**
     * Get total ratings count for a story
     */
    public function getTotalRatings(int $storyId): int
    {
        return $this->where('story_id', $storyId)->countAllResults();
    }
}
