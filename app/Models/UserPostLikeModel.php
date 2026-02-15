<?php
namespace App\Models;

use CodeIgniter\Model;

class UserPostLikeModel extends Model
{
    protected $table = 'user_post_likes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_post_id', 'user_id', 'created_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function getTotalLikesByPost($userPostId)
    {
        return $this->where('user_post_id', $userPostId)->countAllResults();
    }

    public function isLikedByUser($userPostId, $userId)
    {
        return $this->where(['user_post_id' => $userPostId, 'user_id' => $userId])->countAllResults() > 0;
    }
}
