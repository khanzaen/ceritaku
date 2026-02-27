<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserLibraryModel;

class LibraryManagementController extends BaseController
{
    protected $libraryModel;

    public function __construct()
    {
        $this->libraryModel = new UserLibraryModel();
    }

    private function checkAdmin()
    {
        if (!session()->get('isLoggedIn') || strtoupper(session()->get('user_role')) !== 'ADMIN') {
            return redirect()->to('/')->with('error', 'Akses ditolak');
        }
        return null;
    }

    /**
     * List all library entries
     */
    public function index()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        $libraries = $db->table('user_library')
            ->select('
                user_library.*,
                users.name as user_name,
                users.profile_photo as user_photo,
                users.email as user_email,
                stories.title as story_title,
                stories.cover_image as story_cover,
                stories.status as story_status,
                stories.id as story_id,
                author.name as author_name,
                (SELECT COUNT(*) FROM chapters WHERE chapters.story_id = stories.id AND chapters.status = "PUBLISHED") as total_chapters
            ')
            ->join('users', 'users.id = user_library.user_id', 'left')
            ->join('stories', 'stories.id = user_library.story_id', 'left')
            ->join('users as author', 'author.id = stories.author_id', 'left')
            ->orderBy('user_library.updated_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'     => 'Library Management',
            'libraries' => $libraries,
        ];

        return view('admin/library/index', $data);
    }

    /**
     * Get library entry detail (JSON for slide-over)
     */
    public function detail($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        $entry = $db->table('user_library')
            ->select('
                user_library.*,
                users.name as user_name,
                users.email as user_email,
                users.profile_photo as user_photo,
                stories.title as story_title,
                stories.cover_image as story_cover,
                stories.status as story_status,
                stories.description as story_description,
                stories.id as story_id,
                author.name as author_name,
                (SELECT COUNT(*) FROM chapters WHERE chapters.story_id = stories.id AND chapters.status = "PUBLISHED") as total_chapters
            ')
            ->join('users', 'users.id = user_library.user_id', 'left')
            ->join('stories', 'stories.id = user_library.story_id', 'left')
            ->join('users as author', 'author.id = stories.author_id', 'left')
            ->where('user_library.id', $id)
            ->get()
            ->getRowArray();

        if (!$entry) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Library entry not found']);
        }

        // Progress percent
        $totalChapters = (int)($entry['total_chapters'] ?? 0);
        $progress      = (int)($entry['progress'] ?? 0);
        $entry['progress_percent'] = ($totalChapters > 0) ? min(100, round(($progress / $totalChapters) * 100)) : 0;

        return $this->response->setJSON($entry);
    }

    /**
     * Delete library entry
     */
    public function delete($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->libraryModel->delete($id)) {
            return redirect()->back()->with('success', 'Library entry berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Gagal menghapus library entry');
    }
}
