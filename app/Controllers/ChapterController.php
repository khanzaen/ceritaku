<?php

namespace App\Controllers;

use App\Models\ChapterModel;
use App\Models\CommentModel;
use App\Models\UserLibraryModel;
use App\Models\ViewModel;

class ChapterController extends BaseController
{
    protected $chapterModel;
    protected $commentModel;
    protected $libraryModel;
    protected $viewModel;

    public function __construct()
    {
        $this->chapterModel = new ChapterModel();
        $this->commentModel = new CommentModel();
        $this->libraryModel = new UserLibraryModel();
        $this->viewModel = new ViewModel();
    }

    /**
     * Read chapter
     */
    public function read($id)
    {
        $chapter = $this->chapterModel->getChapterWithStory($id);

        if (!$chapter || $chapter['status'] !== 'PUBLISHED') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Chapter not found');
        }

        // Check if premium and user has access
        if ($chapter['is_premium']) {
            if (!session()->get('isLoggedIn')) {
                return redirect()->to('/login')->with('error', 'Chapter premium memerlukan login');
            }
            // Add premium access check here if needed
        }

        // Track view
        $userId = session()->get('user_id');
        $ipAddress = $this->request->getIPAddress();
        
        // Only track if not recently viewed
        if (!$userId || !$this->viewModel->hasRecentView($userId, null, $id, 30)) {
            $this->viewModel->trackView(null, $id, $userId, $ipAddress);
            $this->chapterModel->incrementViewCount($id);
        }

        // Update user progress
        if ($userId) {
            $this->libraryModel->updateProgress($userId, $chapter['story_id'], $chapter['chapter_number']);
        }

        $data = [
            'title' => $chapter['title'] . ' - ' . $chapter['story_title'],
            'chapter' => $chapter,
            'next_chapter' => $this->chapterModel->getNextChapter($chapter['story_id'], $chapter['chapter_number']),
            'prev_chapter' => $this->chapterModel->getPreviousChapter($chapter['story_id'], $chapter['chapter_number']),
            'comments' => $this->commentModel->getCommentsByChapter($id),
            'total_comments' => $this->commentModel->getTotalCommentsByChapter($id),
        ];

        return view('chapter/read', $data);
    }

    /**
     * Add comment to chapter
     */
    public function addComment($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk berkomentar');
        }

        $comment = $this->request->getPost('comment');
        $userId = session()->get('user_id');

        $data = [
            'chapter_id' => $id,
            'user_id' => $userId,
            'comment' => $comment,
        ];

        if ($this->commentModel->insert($data)) {
            return redirect()->back()->with('success', 'Komentar berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan komentar');
    }
}
