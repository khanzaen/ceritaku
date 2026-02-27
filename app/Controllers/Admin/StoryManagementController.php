<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StoryModel;
use App\Models\ChapterModel;

class StoryManagementController extends BaseController
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
                ->select('stories.*, users.name as author_name, users.profile_photo as author_photo')
                ->join('users', 'users.id = stories.author_id')
                ->orderBy('stories.created_at', 'DESC')
                ->findAll(),
        ];

        return view('admin/story/index', $data);
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
     * Get story detail (JSON for slide-over panel)
     */
    public function detail($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $story = $this->storyModel
            ->select('stories.*, users.name as author_name, users.email as author_email, users.profile_photo as author_photo, COUNT(DISTINCT chapters.id) as total_chapters')
            ->join('users', 'users.id = stories.author_id')
            ->join('chapters', 'chapters.story_id = stories.id', 'left')
            ->where('stories.id', $id)
            ->groupBy('stories.id')
            ->first();

        if (!$story) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Story not found']);
        }

        return $this->response->setJSON($story);
    }

    /**
     * Update story status & featured from slide-over panel
     */
    public function update($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $status     = $this->request->getPost('status');
        $isFeatured = $this->request->getPost('is_featured');
        $pubStatus  = $this->request->getPost('publication_status');

        // ── Validasi status sistem ────────────────────────────────────────────
        $allowedStatus = ['DRAFT', 'PENDING_REVIEW', 'PUBLISHED', 'ARCHIVED'];
        if (!in_array($status, $allowedStatus)) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $data = ['status' => $status];

        // ── is_featured ───────────────────────────────────────────────────────
        if ($isFeatured !== null) {
            $data['is_featured'] = (int)$isFeatured;
        }

        // ── publication_status ────────────────────────────────────────────────
        // Hanya berlaku ketika status sedang atau akan menjadi PUBLISHED.
        // Jika admin mengarsipkan/men-draft cerita, publication_status tidak diubah
        // sehingga nilainya tetap tersimpan dan bisa dipulihkan kapan saja.
        $allowedPubStatus = ['Ongoing', 'Completed', 'On Hiatus'];
        if ($status === 'PUBLISHED' && $pubStatus && in_array($pubStatus, $allowedPubStatus)) {
            $data['publication_status'] = $pubStatus;
        }

        if ($this->storyModel->update($id, $data)) {
            return redirect()->to(base_url('/admin/stories'))->with('success', 'Story berhasil diperbarui.');
        }
        return redirect()->back()->with('error', 'Gagal memperbarui story.');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $story = $this->storyModel->find($id);
        if (!$story) return redirect()->back()->with('error', 'Story not found');

        $this->storyModel->update($id, ['is_featured' => $story['is_featured'] ? 0 : 1]);
        return redirect()->back()->with('success', 'Featured status updated.');
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