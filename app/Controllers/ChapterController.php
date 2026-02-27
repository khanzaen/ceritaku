<?php

namespace App\Controllers;

use App\Models\ChapterModel;
use App\Models\CommentModel;
use App\Models\StoryModel;
use App\Models\UserLibraryModel;

class ChapterController extends BaseController
{
    protected $chapterModel;
    protected $commentModel;
    protected $storyModel;
    protected $libraryModel;

    public function __construct()
    {
        $this->chapterModel = new ChapterModel();
        $this->commentModel = new CommentModel();
        $this->storyModel   = new StoryModel();
        $this->libraryModel = new UserLibraryModel();
    }

    /**
     * Read chapter (public)
     */
    public function read($id)
    {
        $chapter = $this->chapterModel->getChapterWithStory($id);

        if (!$chapter || $chapter['status'] !== 'PUBLISHED') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Chapter not found');
        }

        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please log in to read this chapter');
        }

        $userId = session()->get('user_id');

        if ($userId) {
            $this->libraryModel->updateProgress($userId, $chapter['story_id'], $chapter['chapter_number']);
        }

        $data = [
            'title'          => $chapter['title'] . ' - ' . $chapter['story_title'],
            'chapter'        => $chapter,
            'next_chapter'   => $this->chapterModel->getNextChapter($chapter['story_id'], $chapter['chapter_number']),
            'prev_chapter'   => $this->chapterModel->getPreviousChapter($chapter['story_id'], $chapter['chapter_number']),
            'comments'       => $this->commentModel->getCommentsByChapter($id),
            'total_comments' => $this->commentModel->getTotalCommentsByChapter($id),
            'all_chapters'   => $this->chapterModel->getChaptersByStory($chapter['story_id']),
            'chapter_count'  => $this->chapterModel->getChapterCountPerStory($chapter['story_id']),
        ];

        return view('pages/chapter/read', $data);
    }

    /**
     * Form tambah chapter baru
     */
    public function create($storyId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $story = $this->storyModel->find($storyId);

        if (!$story) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Story not found');
        }

        if ($story['author_id'] != session()->get('user_id')) {
            return redirect()->to('/my-stories')->with('error', 'You do not have access to this story');
        }

        // Hitung chapter berikutnya
        $nextNumber = $this->chapterModel->where('story_id', $storyId)->countAllResults() + 1;

        $data = [
            'title'        => 'Tambah Chapter - ' . $story['title'],
            'story'        => $story,
            'next_number'  => $nextNumber,
            'chapter'      => null,
            'validation'   => \Config\Services::validation(),
        ];

        return view('pages/chapter/create', $data);
    }

    /**
     * Simpan chapter baru
     */
    public function save($storyId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $story = $this->storyModel->find($storyId);

        if (!$story || $story['author_id'] != session()->get('user_id')) {
            return redirect()->to('/my-stories')->with('error', 'Access denied');
        }

        $rules = [
            'chapter_title'   => 'required|min_length[3]|max_length[150]',
            'chapter_content' => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nextNumber = $this->chapterModel->where('story_id', $storyId)->countAllResults() + 1;
        $isDraft    = $this->request->getPost('save_draft') ? true : false;

        $chapterData = [
            'story_id'       => $storyId,
            'title'          => $this->request->getPost('chapter_title'),
            'content'        => $this->request->getPost('chapter_content'),
            'chapter_number' => $nextNumber,
            'is_premium'     => $this->request->getPost('is_premium') ? 1 : 0,
            'status'         => $isDraft ? 'DRAFT' : 'PUBLISHED',
        ];

        if ($this->chapterModel->insert($chapterData)) {
            $msg = $isDraft ? 'Chapter saved as draft' : 'Chapter published successfully';
            return redirect()->to('/story/edit/' . $storyId . '?tab=chapters')->with('success', $msg);
        }

        return redirect()->back()->withInput()->with('error', 'Failed to save chapter');
    }

    /**
     * Form edit chapter
     */
    public function edit($storyId, $chapterId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $story   = $this->storyModel->find($storyId);
        $chapter = $this->chapterModel->find($chapterId);

        if (!$story || !$chapter) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Not found');
        }

        if ($story['author_id'] != session()->get('user_id')) {
            return redirect()->to('/my-stories')->with('error', 'You do not have access to this story');
        }

        if ($chapter['story_id'] != $storyId) {
            return redirect()->to('/story/edit/' . $storyId . '?tab=chapters')->with('error', 'Chapter not found');
        }

        $data = [
            'title'      => 'Edit Chapter - ' . $chapter['title'],
            'story'      => $story,
            'chapter'    => $chapter,
            'validation' => \Config\Services::validation(),
        ];

        return view('pages/chapter/edit', $data);
    }

    /**
     * Update chapter
     */
    public function update($storyId, $chapterId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $story   = $this->storyModel->find($storyId);
        $chapter = $this->chapterModel->find($chapterId);

        if (!$story || !$chapter || $story['author_id'] != session()->get('user_id') || $chapter['story_id'] != $storyId) {
            return redirect()->to('/my-stories')->with('error', 'Access denied');
        }

        $rules = [
            'chapter_title'   => 'required|min_length[3]|max_length[150]',
            'chapter_content' => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $isDraft = $this->request->getPost('save_draft') ? true : false;

        $updateData = [
            'title'      => $this->request->getPost('chapter_title'),
            'content'    => $this->request->getPost('chapter_content'),
            'is_premium' => $this->request->getPost('is_premium') ? 1 : 0,
            'status'     => $isDraft ? 'DRAFT' : 'PUBLISHED',
        ];

        if ($this->chapterModel->update($chapterId, $updateData)) {
            $msg = $isDraft ? 'Chapter saved as draft' : 'Chapter updated successfully';
            return redirect()->to('/story/edit/' . $storyId . '?tab=chapters')->with('success', $msg);
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update chapter');
    }

    /**
     * Hapus chapter
     */
    public function deleteChapter($storyId, $chapterId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $story   = $this->storyModel->find($storyId);
        $chapter = $this->chapterModel->find($chapterId);

        if (!$story || !$chapter || $story['author_id'] != session()->get('user_id') || $chapter['story_id'] != $storyId) {
            return redirect()->to('/my-stories')->with('error', 'Access denied');
        }

        if ($this->chapterModel->delete($chapterId)) {
            // Reorder chapter numbers
            $this->reorderChapters($storyId);
            return redirect()->to('/story/edit/' . $storyId . '?tab=chapters')->with('success', 'Chapter deleted successfully');
        }

        return redirect()->to('/story/edit/' . $storyId . '?tab=chapters')->with('error', 'Failed to delete chapter');
    }

    /**
     * Reorder chapter_number setelah delete
     */
    private function reorderChapters($storyId)
    {
        $chapters = $this->chapterModel
            ->where('story_id', $storyId)
            ->orderBy('chapter_number', 'ASC')
            ->findAll();

        foreach ($chapters as $i => $ch) {
            $this->chapterModel->update($ch['id'], ['chapter_number' => $i + 1]);
        }
    }

    /**
     * Add comment to chapter
     */
    public function addComment($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please log in to leave a comment');
        }

        $data = [
            'chapter_id' => $id,
            'user_id'    => session()->get('user_id'),
            'comment'    => $this->request->getPost('comment'),
        ];

        if ($this->commentModel->insert($data)) {
            return redirect()->back()->with('success', 'Comment added successfully');
        }

        return redirect()->back()->with('error', 'Failed to add comment');
    }
}