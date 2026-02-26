<?php

namespace App\Models;

use CodeIgniter\Model;

class UserLibraryModel extends Model
{
    protected $table            = 'user_library';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'story_id',
        'progress',
        'is_reading',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'added_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'user_id'    => 'required|integer',
        'story_id'   => 'required|integer',
        'progress'   => 'integer',
        'is_reading' => 'in_list[0,1]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    /**
     * Get user's library (bookmarked stories)
     */
    public function getUserLibrary(int $userId, bool $isReading = null)
    {
        $builder = $this->select('user_library.*, 
                stories.title,
                stories.cover_image,
                stories.description,
                users.name as author_name')
            ->join('stories', 'stories.id = user_library.story_id')
            ->join('users', 'users.id = stories.author_id')
            ->where('user_library.user_id', $userId);

        if ($isReading !== null) {
            $builder->where('user_library.status', $isReading ? 'reading' : 'finished');
        }

        return $builder->orderBy('user_library.updated_at', 'DESC')->findAll();
    }

    /**
     * Check if story is in user's library
     */
    public function isInLibrary(int $userId, int $storyId): bool
    {
        return $this->where('user_id', $userId)
            ->where('story_id', $storyId)
            ->countAllResults() > 0;
    }

    /**
     * Add story to library
     */
    public function addToLibrary(int $userId, int $storyId): bool
    {
        // Check if already exists
        if ($this->isInLibrary($userId, $storyId)) {
            return false;
        }

        return $this->insert([
            'user_id'    => $userId,
            'story_id'   => $storyId,
            'progress'   => 0,
            'is_reading' => 1,
            'status'     => 'reading'
        ]) !== false;
    }

    /**
     * Remove story from library
     */
    public function removeFromLibrary(int $userId, int $storyId): bool
    {
        return $this->where('user_id', $userId)
            ->where('story_id', $storyId)
            ->delete();
    }

    /**
     * Update reading progress
     */
    public function updateProgress(int $userId, int $storyId, int $chapterNumber): bool
    {
        $entry = $this->where('user_id', $userId)
            ->where('story_id', $storyId)
            ->first();

        if ($entry) {
            // Cek chapter terakhir
            $chapterModel = new \App\Models\ChapterModel();
            $lastChapter = $chapterModel->where('story_id', $storyId)
                ->orderBy('chapter_number', 'DESC')
                ->first();
            $isFinished = $lastChapter && $chapterNumber >= $lastChapter['chapter_number'];
            $updateData = [
                'progress' => $chapterNumber
            ];
            if ($isFinished) {
                $updateData['status'] = 'finished';
                $updateData['is_reading'] = 0;
            }
            return $this->update($entry['id'], $updateData);
        }
        return false;
    }

    /**
     * Mark story as finished
     */
    public function markAsFinished(int $userId, int $storyId): bool
    {
        $entry = $this->where('user_id', $userId)
            ->where('story_id', $storyId)
            ->first();

        if ($entry) {
            return $this->update($entry['id'], [
                'is_reading' => 0,
                'status'     => 'finished'
            ]);
        }
        return false;
    }

    /**
     * Get reading progress for a story
     */
    public function getProgress(int $userId, int $storyId)
    {
        return $this->where('user_id', $userId)
            ->where('story_id', $storyId)
            ->first();
    }

    /**
     * Get chapter_id of last read chapter based on progress (chapter_number)
     * Returns chapter id or null if no progress yet
     */
    public function getLastReadChapterId(int $userId, int $storyId): ?int
    {
        $entry = $this->getProgress($userId, $storyId);
        if (!$entry || (int)$entry['progress'] === 0) return null;

        $chapterModel = new \App\Models\ChapterModel();
        $chapter = $chapterModel->where('story_id', $storyId)
            ->where('chapter_number', (int)$entry['progress'])
            ->first();

        return $chapter ? (int)$chapter['id'] : null;
    }

    /**
     * Get progress percent for a story in user's library
     * Returns integer percent (0-100)
     */
    public function getProgressPercent(int $userId, int $storyId): int
    {
        $entry = $this->getProgress($userId, $storyId);
        if (!$entry) return 0;
        $chapterModel = new \App\Models\ChapterModel();
        $totalChapters = $chapterModel->where('story_id', $storyId)->countAllResults();
        if ($totalChapters <= 0) return 0;
        $progress = (int)$entry['progress'];
        $percent = (int) round(($progress / $totalChapters) * 100);
        // Clamp between 0 and 100
        return max(0, min(100, $percent));
    }
}
