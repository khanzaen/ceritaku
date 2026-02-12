<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table            = 'comments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'chapter_id',
        'user_id',
        'comment'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'chapter_id' => 'required|integer',
        'user_id'    => 'required|integer',
        'comment'    => 'required|min_length[3]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    /**
     * Get comments by chapter with user info
     */
    public function getCommentsByChapter(int $chapterId, int $limit = null)
    {
        $builder = $this->select('comments.*, 
                users.name as user_name,
                users.profile_photo as user_photo')
            ->join('users', 'users.id = comments.user_id')
            ->where('comments.chapter_id', $chapterId)
            ->orderBy('comments.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get total comments for a chapter
     */
    public function getTotalCommentsByChapter(int $chapterId): int
    {
        return $this->where('chapter_id', $chapterId)->countAllResults();
    }

    /**
     * Get total comments for a story (all chapters)
     */
    public function getTotalCommentsByStory(int $storyId): int
    {
        return $this->select('comments.*')
            ->join('chapters', 'chapters.id = comments.chapter_id')
            ->where('chapters.story_id', $storyId)
            ->countAllResults();
    }

    /**
     * Get latest comments for homepage/dashboard
     */
    public function getLatestComments(int $limit = 10)
    {
        return $this->select('comments.*, 
                users.name as user_name,
                users.profile_photo as user_photo,
                chapters.title as chapter_title,
                stories.title as story_title')
            ->join('users', 'users.id = comments.user_id')
            ->join('chapters', 'chapters.id = comments.chapter_id')
            ->join('stories', 'stories.id = chapters.story_id')
            ->orderBy('comments.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get user's comments
     */
    public function getUserComments(int $userId, int $limit = null)
    {
        $builder = $this->select('comments.*, 
                chapters.title as chapter_title,
                stories.title as story_title,
                stories.id as story_id')
            ->join('chapters', 'chapters.id = comments.chapter_id')
            ->join('stories', 'stories.id = chapters.story_id')
            ->where('comments.user_id', $userId)
            ->orderBy('comments.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }
}
