<?php

namespace App\Models;

use CodeIgniter\Model;

class ViewModel extends Model
{
    protected $table            = 'views';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'story_id',
        'chapter_id',
        'ip_address'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    // Validation
    protected $validationRules      = [
        'story_id'   => 'permit_empty|integer',
        'chapter_id' => 'permit_empty|integer',
        'user_id'    => 'permit_empty|integer',
        'ip_address' => 'permit_empty|valid_ip'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    /**
     * Track a view
     */
    public function trackView(int $storyId = null, int $chapterId = null, int $userId = null, string $ipAddress = null): bool
    {
        return $this->insert([
            'user_id'    => $userId,
            'story_id'   => $storyId,
            'chapter_id' => $chapterId,
            'ip_address' => $ipAddress
        ]) !== false;
    }

    /**
     * Get total views for a story
     */
    public function getTotalViewsByStory(int $storyId): int
    {
        return $this->where('story_id', $storyId)->countAllResults();
    }

    /**
     * Get total views for a chapter
     */
    public function getTotalViewsByChapter(int $chapterId): int
    {
        return $this->where('chapter_id', $chapterId)->countAllResults();
    }

    /**
     * Get unique viewers for a story
     */
    public function getUniqueViewersByStory(int $storyId): int
    {
        return $this->select('COUNT(DISTINCT user_id) as unique_viewers')
            ->where('story_id', $storyId)
            ->where('user_id IS NOT NULL')
            ->first()['unique_viewers'] ?? 0;
    }

    /**
     * Get viewing stats for a story
     */
    public function getStoryStats(int $storyId): array
    {
        $totalViews = $this->getTotalViewsByStory($storyId);
        $uniqueViewers = $this->getUniqueViewersByStory($storyId);

        return [
            'total_views'    => $totalViews,
            'unique_viewers' => $uniqueViewers
        ];
    }

    /**
     * Get most viewed stories
     */
    public function getMostViewedStories(int $limit = 10)
    {
        return $this->select('stories.*, 
                users.name as author_name,
                COUNT(views.id) as view_count')
            ->join('stories', 'stories.id = views.story_id')
            ->join('users', 'users.id = stories.author_id')
            ->where('stories.status', 'PUBLISHED')
            ->groupBy('views.story_id')
            ->orderBy('view_count', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Check if user has viewed a story/chapter recently (untuk prevent duplicate views)
     */
    public function hasRecentView(int $userId, int $storyId = null, int $chapterId = null, int $minutes = 30): bool
    {
        $builder = $this->where('user_id', $userId)
            ->where('created_at >', date('Y-m-d H:i:s', strtotime("-{$minutes} minutes")));

        if ($storyId) {
            $builder->where('story_id', $storyId);
        }

        if ($chapterId) {
            $builder->where('chapter_id', $chapterId);
        }

        return $builder->countAllResults() > 0;
    }
}
