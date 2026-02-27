<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommentModel;

class CommentManagementController extends BaseController
{
    protected $commentModel;

    public function __construct()
    {
        $this->commentModel = new CommentModel();
    }

    private function checkAdmin()
    {
        if (!session()->get('isLoggedIn') || strtoupper(session()->get('user_role')) !== 'ADMIN') {
            return redirect()->to('/')->with('error', 'Akses ditolak');
        }
        return null;
    }

    /**
     * List all comments
     */
    public function index()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        $comments = $db->table('comments')
            ->select('
                comments.*,
                users.name as user_name,
                users.profile_photo as user_photo,
                users.email as user_email,
                chapters.title as chapter_title,
                chapters.chapter_number,
                chapters.id as chapter_id,
                stories.title as story_title,
                stories.id as story_id,
                author.name as author_name
            ')
            ->join('users', 'users.id = comments.user_id', 'left')
            ->join('chapters', 'chapters.id = comments.chapter_id', 'left')
            ->join('stories', 'stories.id = chapters.story_id', 'left')
            ->join('users as author', 'author.id = stories.author_id', 'left')
            ->orderBy('comments.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'    => 'Comment Management',
            'comments' => $comments,
        ];

        return view('admin/comment/index', $data);
    }

    /**
     * Get comment detail (JSON for slide-over)
     */
    public function detail($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        $comment = $db->table('comments')
            ->select('
                comments.*,
                users.name as user_name,
                users.email as user_email,
                users.profile_photo as user_photo,
                chapters.title as chapter_title,
                chapters.chapter_number,
                chapters.id as chapter_id,
                stories.title as story_title,
                stories.id as story_id,
                author.name as author_name,
                author.email as author_email
            ')
            ->join('users', 'users.id = comments.user_id', 'left')
            ->join('chapters', 'chapters.id = comments.chapter_id', 'left')
            ->join('stories', 'stories.id = chapters.story_id', 'left')
            ->join('users as author', 'author.id = stories.author_id', 'left')
            ->where('comments.id', $id)
            ->get()
            ->getRowArray();

        if (!$comment) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Comment not found']);
        }

        return $this->response->setJSON($comment);
    }

    /**
     * Delete comment
     */
    public function delete($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->commentModel->delete($id)) {
            return redirect()->back()->with('success', 'Comment berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Gagal menghapus comment');
    }
}
