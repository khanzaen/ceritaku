<?php

namespace App\Models;

use CodeIgniter\Model;

class ChapterModel extends Model
{
    /**
     * Get chapter count per story (all statuses)
     */
    public function getChapterCountPerStory(int $storyId): int
    {
        return $this->where('story_id', $storyId)->countAllResults();
    }
    protected $table            = 'chapters';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'story_id',
        'title',
        'chapter_number',
        'content',
        'is_premium',
        'status',
        'created_at',
        'updated_at'
        // 'author_note' - DIHAPUS karena tidak ada di tabel
        // 'view_count' - DIHAPUS karena sudah dihapus
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'story_id'       => 'required|integer',
        'title'          => 'required|min_length[3]|max_length[150]',
        'chapter_number' => 'required|integer',
        'content'        => 'required',
        'is_premium'     => 'permit_empty|in_list[0,1]',
        'status'         => 'required|in_list[DRAFT,PUBLISHED,ARCHIVED]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setDefaultValues'];
    protected $beforeUpdate   = [];

    /**
     * Set default values before insert
     */
    protected function setDefaultValues(array $data)
    {
        if (!isset($data['data']['is_premium'])) {
            $data['data']['is_premium'] = 0;
        }
        return $data;
    }

    /**
     * Get chapters by story
     */
    public function getChaptersByStory(int $storyId, string $status = 'PUBLISHED')
    {
        return $this->where('story_id', $storyId)
            ->where('status', $status)
            ->orderBy('chapter_number', 'ASC')
            ->findAll();
    }

    /**
     * Get single chapter with story info
     */
    public function getChapterWithStory(int $chapterId)
    {
        return $this->select('chapters.*, 
                stories.title as story_title,
                stories.author_id,
                users.name as author_name')
            ->join('stories', 'stories.id = chapters.story_id')
            ->join('users', 'users.id = stories.author_id')
            ->where('chapters.id', $chapterId)
            ->first();
    }

    /**
     * Get next chapter
     */
    public function getNextChapter(int $storyId, int $currentChapterNumber)
    {
        return $this->where('story_id', $storyId)
            ->where('chapter_number >', $currentChapterNumber)
            ->where('status', 'PUBLISHED')
            ->orderBy('chapter_number', 'ASC')
            ->first();
    }

    /**
     * Get previous chapter
     */
    public function getPreviousChapter(int $storyId, int $currentChapterNumber)
    {
        return $this->where('story_id', $storyId)
            ->where('chapter_number <', $currentChapterNumber)
            ->where('status', 'PUBLISHED')
            ->orderBy('chapter_number', 'DESC')
            ->first();
    }

    /**
     * Get total chapters in a story
     */
    public function getTotalChaptersByStory(int $storyId, string $status = 'PUBLISHED'): int
    {
        return $this->where('story_id', $storyId)
            ->where('status', $status)
            ->countAllResults();
    }

    /**
     * Get latest published chapters (for homepage)
     */
    public function getLatestChapters(int $limit = 10)
    {
        return $this->select('chapters.*, 
                stories.title as story_title,
                users.name as author_name')
            ->join('stories', 'stories.id = chapters.story_id')
            ->join('users', 'users.id = stories.author_id')
            ->where('chapters.status', 'PUBLISHED')
            ->where('stories.status', 'PUBLISHED')
            ->orderBy('chapters.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}