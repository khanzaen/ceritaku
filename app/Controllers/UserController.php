<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\StoryModel;
use App\Models\UserLibraryModel;
use App\Models\ReviewModel;
use App\Models\CommentModel;


class UserController extends BaseController
{
    protected $userModel;
    protected $storyModel;
    protected $libraryModel;
    protected $reviewModel;
    protected $commentModel;


    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->storyModel = new StoryModel();
        $this->libraryModel = new UserLibraryModel();
        $this->reviewModel = new ReviewModel();
        $this->commentModel = new CommentModel();

    }

    /**
     * View user/author profile (public view)
     */
    public function viewUser($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }

        $stories = $this->storyModel->getStoriesByAuthor($id, 'PUBLISHED');

        // Calculate total reads
        $totalReads = 0;
        foreach ($stories as $story) {
            $totalReads += $story['total_views'] ?? 0;
        }

        $data = [
            'title' => $user['name'] . ' - Author Profile',
            'author' => $user,
            'stories' => $stories,
            'total_stories' => count($stories),
            'total_reads' => $totalReads,
            'posts' => [],
        ];

        return view('pages/user/user-info', $data);
    }

    /**
     * User profile page
     */
    public function profile($id = null)
    {
        // If no ID provided, show current user's profile
        if (!$id && session()->get('isLoggedIn')) {
            $id = session()->get('user_id');
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('User not found');
        }

        $stories = $this->storyModel->getStoriesByAuthor($id, 'PUBLISHED');
        
        // Calculate total reads
        $totalReads = 0;
        foreach ($stories as $story) {
            $totalReads += $story['total_views'] ?? 0;
        }

        $data = [
            'title' => $user['name'] . ' - Author Profile',
            'author' => $user,
            'stories' => $stories,
            'total_stories' => count($stories),
            'total_reads' => $totalReads,
            'followers' => 0, // TODO: Implement followers feature
        ];

        // Profil sendiri pakai my-profile, profil orang lain pakai user-info
        $isOwnProfile = session()->get('isLoggedIn') && session()->get('user_id') == $id;
        $viewFile = $isOwnProfile ? 'pages/user/my-profile' : 'pages/user/user-info';

        return view($viewFile, $data);
    }

    /**
     * User library (bookmarked stories)
     */
    public function library()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');
        
        $data = [
            'title' => 'My Library',
            'reading' => $this->libraryModel->getUserLibrary($userId, true),
            'finished' => $this->libraryModel->getUserLibrary($userId, false),
        ];

        return view('user/library', $data);
    }

    /**
     * User reviews
     */
    public function reviews()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        $data = [
            'title' => 'My Reviews',
            'reviews' => $this->reviewModel->select('reviews.*, stories.title as story_title, stories.cover_image')
                ->join('stories', 'stories.id = reviews.story_id')
                ->where('reviews.user_id', $userId)
                ->orderBy('reviews.created_at', 'DESC')
                ->findAll(),
        ];

        return view('user/reviews', $data);
    }

    /**
     * User comments
     */
    public function comments()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        $data = [
            'title' => 'My Comments',
            'comments' => $this->commentModel->getUserComments($userId),
        ];

        return view('user/comments', $data);
    }

    /**
     * Edit profile
     */
    public function editProfile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $data = [
            'title' => 'Edit Profile',
            'user' => $user,
        ];

        return view('pages/user/edit-profile', $data);
    }

    /**
     * Update profile
     */
    public function updateProfile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'username' => 'permit_empty|alpha_numeric|min_length[3]|max_length[50]',
            'bio' => 'permit_empty|max_length[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'bio' => $this->request->getPost('bio'),
        ];

        // Handle profile photo upload
        $file = $this->request->getFile('profile_photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/profiles', $newName);
            $data['profile_photo'] = 'profiles/' . $newName;
        }

        if ($this->userModel->skipValidation(true)->update($userId, $data)) {
            // Update session
            session()->set('name', $data['name']);
            session()->set('user_name', $data['name']);
            if (isset($data['profile_photo'])) {
                session()->set('user_photo', $data['profile_photo']);
            }

            return redirect()->to('/profile')->with('success', 'Profile berhasil diupdate');
        }

        return redirect()->back()->with('error', 'Gagal update profile')->withInput();
    }

    /**
     * Follow/unfollow user
     */
    public function follow($id)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Login dulu']);
        }
        $userId = session()->get('user_id');
        if ($userId == $id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak bisa follow diri sendiri']);
        }
        // Fitur follow di-nonaktifkan
        return $this->response->setJSON(['success' => false, 'message' => 'Fitur follow dinonaktifkan']);
    }
}