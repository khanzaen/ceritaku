<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\StoryModel;
use App\Models\UserLibraryModel;
use App\Models\ReviewModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $storyModel;
    protected $libraryModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->storyModel   = new StoryModel();
        $this->libraryModel = new UserLibraryModel();
        $this->reviewModel  = new ReviewModel();
    }

    // ─── Helper: ambil user ID dari JWT payload ───────────────────────────────

    private function getAuthUserId(): ?int
    {
        $payload = json_decode($_SERVER['JWT_PAYLOAD'] ?? '{}', true);
        return isset($payload['sub']) ? (int) $payload['sub'] : null;
    }
    public function show($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan',
            ]);
        }

        $stories    = $this->storyModel->getStoriesByAuthor($id, 'PUBLISHED');
        $totalReads = array_sum(array_column($stories, 'total_views'));

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'id'            => $user['id'],
                'name'          => $user['name'],
                'username'      => $user['username'] ?? null,
                'bio'           => $user['bio'] ?? null,
                'profile_photo' => $user['profile_photo'] ?? null,
                'total_stories' => count($stories),
                'total_reads'   => $totalReads,
                'stories'       => $stories,
            ],
        ]);
    }

    // ─── [PROTECTED] GET /api/profile — Profil milik sendiri ─────────────────

    public function profile()
    {
        $userId = $this->getAuthUserId();
        $user   = $this->userModel->find($userId);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan',
            ]);
        }

        $stories    = $this->storyModel->getStoriesByAuthor($userId, 'PUBLISHED');
        $totalReads = array_sum(array_column($stories, 'total_views'));

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'id'            => $user['id'],
                'name'          => $user['name'],
                'username'      => $user['username'] ?? null,
                'email'         => $user['email'],
                'bio'           => $user['bio'] ?? null,
                'profile_photo' => $user['profile_photo'] ?? null,
                'role'          => $user['role'] ?? 'USER',
                'total_stories' => count($stories),
                'total_reads'   => $totalReads,
            ],
        ]);
    }

    // ─── [PROTECTED] PUT /api/profile — Update profil ────────────────────────

    public function updateProfile()
    {
        $userId = $this->getAuthUserId();

        $json = $this->request->getJSON(true);
        $name     = $this->request->getPost('name')     ?? ($json['name']     ?? null);
        $username = $this->request->getPost('username') ?? ($json['username'] ?? null);
        $bio      = $this->request->getPost('bio')      ?? ($json['bio']      ?? null);

        if (empty($name)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Nama tidak boleh kosong',
            ]);
        }

        $updateData = [
            'name'     => $name,
            'username' => $username,
            'bio'      => $bio,
        ];

        // Handle upload foto profil (multipart/form-data)
        $file = $this->request->getFile('profile_photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status'  => 'error',
                    'message' => 'Format foto harus JPG atau PNG',
                ]);
            }

            $uploadPath = FCPATH . 'uploads/profiles';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $updateData['profile_photo'] = 'profiles/' . $newName;
        }

        if ($this->userModel->skipValidation(true)->update($userId, $updateData)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Profil berhasil diperbarui',
            ]);
        }

        return $this->response->setStatusCode(500)->setJSON([
            'status'  => 'error',
            'message' => 'Gagal memperbarui profil',
        ]);
    }

    // ─── [PROTECTED] GET /api/library — Library/Bookmark user ────────────────

    public function library()
    {
        $userId = $this->getAuthUserId();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'reading'  => $this->libraryModel->getUserLibrary($userId, true),
                'finished' => $this->libraryModel->getUserLibrary($userId, false),
            ],
        ]);
    }

    // ─── [PROTECTED] GET /api/my-reviews — Review yang dibuat user ───────────

    public function myReviews()
    {
        $userId = $this->getAuthUserId();

        $reviews = $this->reviewModel
            ->select('reviews.*, stories.title as story_title, stories.cover_image')
            ->join('stories', 'stories.id = reviews.story_id')
            ->where('reviews.user_id', $userId)
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $reviews,
        ]);
    }
}