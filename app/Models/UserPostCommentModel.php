<?php

namespace App\Models;

use CodeIgniter\Model;

class UserPostCommentModel extends Model
{
    protected $table = 'user_post_comments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_post_id', 'user_id', 'comment', 'created_at', 'updated_at'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getCommentsByPost($userPostId)
    {
        return $this->select('user_post_comments.*, users.name as user_name, users.profile_photo as user_photo')
            ->join('users', 'users.id = user_post_comments.user_id')
            ->where('user_post_comments.user_post_id', $userPostId)
            ->orderBy('user_post_comments.created_at', 'ASC')
            ->findAll();
    }

    public function getTotalCommentsByPost($userPostId)
    {
        return $this->where('user_post_id', $userPostId)->countAllResults();
    }
}
