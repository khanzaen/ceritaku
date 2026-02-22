<?php

namespace App\Controllers;

use App\Models\ChapterModel;
use App\Models\CommentModel;
use App\Models\UserLibraryModel;

class ChapterController extends BaseController
{
    protected $chapterModel;
    protected $commentModel;
    protected $libraryModel;

    public function __construct()
    {
        $this->chapterModel = new ChapterModel();
        $this->commentModel = new CommentModel();
        $this->libraryModel = new UserLibraryModel();
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

        // Require login for all chapters
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'amodalsSilaapageskan_-_- login untuk membaca chapter');
        }

        // Track view
        $userId = session()->get('user_id');
        $ipAddress = $this->request->getIPAddress();

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
            'all_chapters' => $this->chapterModel->getChaptersByStory($chapter['story_id']),
            'chapter_count' => $this->chapterModel->getChapterCountPerStory($chapter['story_id']),
        ];

        return view('pages/read-chapter', $data);
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
