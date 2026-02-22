<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StoryModel;
use App\Models\ChapterModel;

class StoryManagement extends BaseController
{
    protected $storyModel;
    protected $chapterModel;

    public function __construct()
    {
        $this->storyModel   = new StoryModel();
        $this->chapterModel = new ChapterModel();
    }

    /**
     * Check admin access — uses correct session key 'user_role'
     */
    private function checkAdmin()
    {
        if (!session()->get('isLoggedIn') || strtoupper(session()->get('user_role')) !== 'ADMIN') {
            return redirect()->to('/')->with('error', 'Akses ditolak');
        }
        return null;
    }

    /**
     * List all stories
     */
    public function index()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title'   => 'Story Management',
            'stories' => $this->storyModel
                ->select('stories.*, users.name as author_name')
                ->join('users', 'users.id = stories.author_id')
                ->orderBy('stories.created_at', 'DESC')
                ->findAll(),
        ];

        return view('admin/story-management', $data);
    }

    /**
     * Approve pending story
     */
    public function approve($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->storyModel->update($id, ['status' => 'PUBLISHED'])) {
            return redirect()->back()->with('success', 'Story berhasil dipublikasikan');
        }
        return redirect()->back()->with('error', 'Gagal mempublikasikan story');
    }

    /**
     * Archive story
     */
    public function archive($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->storyModel->update($id, ['status' => 'ARCHIVED'])) {
            return redirect()->back()->with('success', 'Story berhasil diarsipkan');
        }
        return redirect()->back()->with('error', 'Gagal mengarsipkan story');
    }

    /**
     * Delete story
     */
    public function delete($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->storyModel->delete($id)) {
            return redirect()->back()->with('success', 'Story berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Gagal menghapus story');
    }
}
