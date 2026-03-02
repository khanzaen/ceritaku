<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\StoryModel;
use App\Models\ChapterModel;
use App\Models\ReviewModel;
use App\Models\UserLibraryModel;
use App\Models\UserModel;

class StoryController extends BaseController
{
    protected $storyModel;
    protected $chapterModel;
    protected $reviewModel;
    protected $libraryModel;
    protected $userModel;

    public function __construct()
    {
        $this->storyModel   = new StoryModel();
        $this->chapterModel = new ChapterModel();
        $this->reviewModel  = new ReviewModel();
        $this->libraryModel = new UserLibraryModel();
        $this->userModel    = new UserModel();
    }

    // ─── Helper: ambil user ID dari JWT payload ───────────────────────────────

    private function getAuthUserId(): ?int
    {
        $payload = json_decode($_SERVER['JWT_PAYLOAD'] ?? '{}', true);
        return isset($payload['sub']) ? (int) $payload['sub'] : null;
    }
    public function index()
    {
        $search = $this->request->getGet('q');
        $genre  = $this->request->getGet('genre');

        if ($search) {
            $stories = $this->storyModel->searchStories($search);
        } elseif ($genre) {
            $stories = $this->storyModel->getStoriesByGenre($genre);
        } else {
            $stories = $this->storyModel->getAllStories();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'total'  => count($stories),
            'data'   => $stories,
        ]);
    }

    // ─── [PUBLIC] GET /api/stories/{id} — Detail satu story ──────────────────

    public function show($id)
    {
        $story = $this->storyModel->getStoryWithDetails($id);

        if (!$story) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Story tidak ditemukan',
            ]);
        }

        $data = [
            'story'         => $story,
            'chapters'      => $this->chapterModel->getChaptersByStory($id),
            'reviews'       => $this->reviewModel->getReviewsByStory($id, 5),
            'chapter_count' => $this->chapterModel->getChapterCountPerStory($id),
            'is_bookmarked' => false,
        ];

        // Cek bookmark jika user login
        $userId = $this->getAuthUserId();
        if ($userId) {
            $data['is_bookmarked'] = $this->libraryModel->isInLibrary($userId, $id);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $data,
        ]);
    }

    // ─── [PROTECTED] GET /api/my-stories — Story milik sendiri ───────────────

    public function myStories()
    {
        $userId   = $this->getAuthUserId();
        $stories  = $this->storyModel
            ->where('author_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $published = [];
        $drafts    = [];

        foreach ($stories as $story) {
            if ($story['status'] === 'PUBLISHED') {
                $published[] = $story;
            } else {
                $drafts[] = $story;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'published' => $published,
                'drafts'    => $drafts,
            ],
        ]);
    }

    // ─── [PROTECTED] POST /api/stories — Buat story baru ─────────────────────

    public function create()
    {
        $userId = $this->getAuthUserId();
        $json   = $this->request->getJSON(true);

        $title    = $this->request->getPost('title')    ?? ($json['title']    ?? null);
        $synopsis = $this->request->getPost('synopsis') ?? ($json['synopsis'] ?? null);
        $genres   = $this->request->getPost('genre')    ?? ($json['genre']    ?? null);
        $status   = $this->request->getPost('status')   ?? ($json['status']   ?? 'DRAFT');

        // Validasi
        $errors = [];
        if (empty($title) || strlen($title) < 3)      $errors['title']    = 'Judul minimal 3 karakter';
        if (empty($synopsis) || strlen($synopsis) < 10) $errors['synopsis'] = 'Sinopsis minimal 10 karakter';
        if (empty($genres))                             $errors['genre']    = 'Pilih minimal 1 genre';
        if (!in_array($status, ['DRAFT', 'PENDING_REVIEW'])) $errors['status'] = 'Status tidak valid';

        if (!empty($errors)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $errors,
            ]);
        }

        // Proses genre
        if (is_array($genres)) {
            $genresString = implode(', ', array_map('ucfirst', $genres));
        } else {
            $genresString = ucfirst($genres);
        }

        // Upload cover (opsional, multipart/form-data)
        $coverPath = null;
        $cover     = $this->request->getFile('cover');
        if ($cover && $cover->isValid() && !$cover->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($cover->getMimeType(), $allowedTypes)) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status'  => 'error',
                    'message' => 'Format cover harus JPG atau PNG',
                ]);
            }

            $uploadPath = FCPATH . 'uploads/covers';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $newName   = $cover->getRandomName();
            $cover->move($uploadPath, $newName);
            $coverPath = 'covers/' . $newName;
        }

        $storyData = [
            'title'              => $title,
            'author_id'          => $userId,
            'description'        => $synopsis,
            'cover_image'        => $coverPath,
            'genres'             => $genresString,
            'status'             => $status,
            'publication_status' => 'Ongoing',
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $storyId = $this->storyModel->insert($storyData);

            if (!$storyId) {
                throw new \Exception('Gagal menyimpan cerita');
            }

            $db->transComplete();

            return $this->response->setStatusCode(201)->setJSON([
                'status'   => 'success',
                'message'  => $status === 'DRAFT' ? 'Story disimpan sebagai draft' : 'Story dikirim untuk review',
                'story_id' => $storyId,
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            if ($coverPath && file_exists(FCPATH . $coverPath)) {
                unlink(FCPATH . $coverPath);
            }

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    // ─── [PROTECTED] PUT /api/stories/{id} — Update story ────────────────────

    public function update($id)
    {
        $userId = $this->getAuthUserId();
        $story  = $this->storyModel->find($id);

        if (!$story) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Story tidak ditemukan',
            ]);
        }

        if ($story['author_id'] != $userId) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke story ini',
            ]);
        }

        $json     = $this->request->getJSON(true);
        $title    = $this->request->getPost('title')    ?? ($json['title']    ?? $story['title']);
        $synopsis = $this->request->getPost('synopsis') ?? ($json['synopsis'] ?? $story['description']);
        $genres   = $this->request->getPost('genre')    ?? ($json['genre']    ?? null);

        $genresString = $story['genres'];
        if (!empty($genres)) {
            $genresString = is_array($genres)
                ? implode(', ', array_map('ucfirst', $genres))
                : ucfirst($genres);
        }

        $updateData = [
            'title'       => $title,
            'description' => $synopsis,
            'genres'      => $genresString,
        ];

        // publication_status hanya bisa diubah jika cerita sudah PUBLISHED
        if ($story['status'] === 'PUBLISHED') {
            $pubStatus = $this->request->getPost('publication_status') ?? ($json['publication_status'] ?? null);
            if ($pubStatus && in_array($pubStatus, ['Ongoing', 'Completed', 'On Hiatus'])) {
                $updateData['publication_status'] = $pubStatus;
            }
        }

        // Handle cover upload
        $cover = $this->request->getFile('cover');
        if ($cover && $cover->isValid() && !$cover->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($cover->getMimeType(), $allowedTypes)) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status'  => 'error',
                    'message' => 'Format cover harus JPG atau PNG',
                ]);
            }

            // Hapus cover lama
            if ($story['cover_image'] && file_exists(FCPATH . 'uploads/' . $story['cover_image'])) {
                unlink(FCPATH . 'uploads/' . $story['cover_image']);
            }

            $uploadPath = FCPATH . 'uploads/covers';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $newName = $cover->getRandomName();
            $cover->move($uploadPath, $newName);
            $updateData['cover_image'] = 'covers/' . $newName;
        }

        if ($this->storyModel->update($id, $updateData)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Story berhasil diperbarui',
            ]);
        }

        return $this->response->setStatusCode(500)->setJSON([
            'status'  => 'error',
            'message' => 'Gagal memperbarui story',
        ]);
    }

    // ─── [PROTECTED] DELETE /api/stories/{id} — Hapus story ──────────────────

    public function delete($id)
    {
        $userId = $this->getAuthUserId();
        $story  = $this->storyModel->find($id);

        if (!$story) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Story tidak ditemukan',
            ]);
        }

        if ($story['author_id'] != $userId) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke story ini',
            ]);
        }

        // Hapus cover
        if ($story['cover_image'] && file_exists(FCPATH . 'uploads/' . $story['cover_image'])) {
            unlink(FCPATH . 'uploads/' . $story['cover_image']);
        }

        // Hapus semua chapter
        $this->chapterModel->where('story_id', $id)->delete();

        if ($this->storyModel->delete($id)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Story berhasil dihapus',
            ]);
        }

        return $this->response->setStatusCode(500)->setJSON([
            'status'  => 'error',
            'message' => 'Gagal menghapus story',
        ]);
    }

    // ─── [PROTECTED] POST /api/stories/{id}/submit — Submit untuk review ─────

    public function submitForReview($id)
    {
        $userId = $this->getAuthUserId();
        $story  = $this->storyModel->find($id);

        if (!$story) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Story tidak ditemukan',
            ]);
        }

        if ($story['author_id'] != $userId) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke story ini',
            ]);
        }

        if (!in_array($story['status'], ['DRAFT', 'PENDING_REVIEW', 'PUBLISHED'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Story tidak bisa disubmit untuk review saat ini',
            ]);
        }

        $this->storyModel->update($id, ['status' => 'PENDING_REVIEW']);
        $this->chapterModel
            ->whereIn('status', ['DRAFT', 'PUBLISHED'])
            ->where('story_id', $id)
            ->set(['status' => 'PENDING_REVIEW'])
            ->update();

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Story berhasil disubmit untuk review',
        ]);
    }

    // ─── [PROTECTED] POST /api/stories/{id}/bookmark — Tambah ke library ─────

    public function addToLibrary($id)
    {
        $userId = $this->getAuthUserId();

        if ($this->libraryModel->addToLibrary($userId, $id)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Story ditambahkan ke library',
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status'  => 'error',
            'message' => 'Story sudah ada di library kamu',
        ]);
    }

    // ─── [PROTECTED] DELETE /api/stories/{id}/bookmark — Hapus dari library ──

    public function removeFromLibrary($id)
    {
        $userId = $this->getAuthUserId();

        if ($this->libraryModel->removeFromLibrary($userId, $id)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Story dihapus dari library',
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status'  => 'error',
            'message' => 'Gagal menghapus story dari library',
        ]);
    }
}