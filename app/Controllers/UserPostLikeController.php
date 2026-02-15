<?php
namespace App\Controllers;

use App\Models\UserPostLikeModel;
use CodeIgniter\Controller;

class UserPostLikeController extends Controller
{
    public function toggle()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Silakan login']);
        }
        $postId = $this->request->getPost('user_post_id');
        if (!$postId) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID postingan tidak valid']);
        }
        $model = new UserPostLikeModel();
        $liked = $model->isLikedByUser($postId, $userId);
        if ($liked) {
            $model->where(['user_post_id' => $postId, 'user_id' => $userId])->delete();
            return $this->response->setJSON(['success' => true, 'liked' => false]);
        } else {
            $model->insert([
                'user_post_id' => $postId,
                'user_id' => $userId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return $this->response->setJSON(['success' => true, 'liked' => true]);
        }
    }
}
