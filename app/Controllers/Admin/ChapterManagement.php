<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ChapterModel;

class ChapterManagement extends BaseController
{
    protected $chapterModel;

    public function __construct()
    {
        $this->chapterModel = new ChapterModel();
    }

    private function checkAdmin()
    {
        if (!session()->get('isLoggedIn') || strtoupper(session()->get('user_role')) !== 'ADMIN') {
            return redirect()->to('/')->with('error', 'Akses ditolak');
        }
        return null;
    }

    public function index()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title'    => 'Chapter Management',
            'chapters' => $this->chapterModel
                ->select('chapters.*, stories.title as story_title, users.name as author_name')
                ->join('stories', 'stories.id = chapters.story_id', 'left')
                ->join('users', 'users.id = stories.author_id', 'left')
                ->orderBy('chapters.created_at', 'DESC')
                ->findAll(),
        ];

        return view('admin/chapter-management', $data);
    }

    public function delete($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->chapterModel->delete($id)) {
            return redirect()->back()->with('success', 'Chapter berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Gagal menghapus chapter');
    }
}
