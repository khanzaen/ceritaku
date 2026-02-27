<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ChapterModel;
use App\Models\StoryModel;

class ChapterManagementController extends BaseController
{
    protected $chapterModel;
    protected $storyModel;

    public function __construct()
    {
        $this->chapterModel = new ChapterModel();
        $this->storyModel   = new StoryModel();
    }

    /**
     * Check admin access
     */
    private function checkAdmin()
    {
        if (!session()->get('isLoggedIn') || strtoupper(session()->get('user_role')) !== 'ADMIN') {
            return redirect()->to('/')->with('error', 'Akses ditolak');
        }
        return null;
    }

    /**
     * List all chapters with story & author info
     */
    public function index()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $chapters = $this->chapterModel
            ->select('chapters.*, stories.title as story_title, stories.id as story_id, users.name as author_name, users.profile_photo as author_photo')
            ->join('stories', 'stories.id = chapters.story_id', 'left')
            ->join('users', 'users.id = stories.author_id', 'left')
            ->orderBy('chapters.created_at', 'DESC')
            ->findAll();

        $data = [
            'title'    => 'Chapter Management',
            'chapters' => $chapters,
        ];

        return view('admin/chapter/index', $data);
    }

    /**
     * Get chapter detail (JSON for slide-over panel)
     */
    public function detail($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $chapter = $this->chapterModel
            ->select('chapters.*, stories.title as story_title, stories.id as story_id, stories.status as story_status, users.name as author_name, users.email as author_email, users.profile_photo as author_photo')
            ->join('stories', 'stories.id = chapters.story_id', 'left')
            ->join('users', 'users.id = stories.author_id', 'left')
            ->where('chapters.id', $id)
            ->first();

        if (!$chapter) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Chapter not found']);
        }

        // Truncate content for preview
        if (!empty($chapter['content'])) {
            $chapter['content_preview'] = mb_substr(strip_tags($chapter['content']), 0, 300);
            $chapter['word_count'] = str_word_count(strip_tags($chapter['content']));
        }

        return $this->response->setJSON($chapter);
    }

    /**
     * Update chapter status
     */
    public function update($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $status    = $this->request->getPost('status');
        $isPremium = $this->request->getPost('is_premium');

        $allowedStatus = ['DRAFT', 'PUBLISHED', 'ARCHIVED'];
        if (!in_array($status, $allowedStatus)) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $updateData = ['status' => $status];

        if ($isPremium !== null) {
            $updateData['is_premium'] = (int) $isPremium;
        }

        if ($this->chapterModel->update($id, $updateData)) {
            return redirect()->to(base_url('/admin/chapters'))->with('success', 'Chapter berhasil diperbarui.');
        }
        return redirect()->back()->with('error', 'Gagal memperbarui chapter.');
    }

    /**
     * Publish chapter
     */
    public function publish($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->chapterModel->update($id, ['status' => 'PUBLISHED'])) {
            return redirect()->back()->with('success', 'Chapter berhasil dipublikasikan');
        }
        return redirect()->back()->with('error', 'Gagal mempublikasikan chapter');
    }

    /**
     * Archive chapter
     */
    public function archive($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->chapterModel->update($id, ['status' => 'ARCHIVED'])) {
            return redirect()->back()->with('success', 'Chapter berhasil diarsipkan');
        }
        return redirect()->back()->with('error', 'Gagal mengarsipkan chapter');
    }

    /**
     * Delete chapter
     */
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
