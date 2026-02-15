<?php
namespace App\Controllers;

use App\Models\UserPostCommentModel;
use CodeIgniter\Controller;

class UserPostCommentController extends Controller
{
    public function add()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        $postId = $this->request->getPost('user_post_id');
        $comment = $this->request->getPost('comment');
        if (!$postId || !$comment) {
            return redirect()->back()->with('error', 'Komentar tidak boleh kosong');
        }
        $model = new UserPostCommentModel();
        $model->insert([
            'user_post_id' => $postId,
            'user_id' => $userId,
            'comment' => $comment,
        ]);
        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan');
    }
}
