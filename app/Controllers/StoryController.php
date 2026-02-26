<?php

namespace App\Controllers;

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
        $this->storyModel = new StoryModel();
        $this->chapterModel = new ChapterModel();
        $this->reviewModel = new ReviewModel();
        $this->libraryModel = new UserLibraryModel();
        $this->userModel = new UserModel();
    }

    /**
     * Form create story
     */

    /**
     * Halaman tulis
     */
    public function write()
    {


        $data = [
            'title' => 'Write'
        ];

        return view('pages/write.php', $data);
    }
    public function create()
    {
        // Cek login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login untuk membuat cerita');
        }

        $data = [
            'title' => 'Write' 
        ];

        return view('pages/story/create', $data);
    }

    /**
     * Simpan story
     */
    public function save()
    {
        // Cek login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Validasi input
        $rules = [
            'title' => [
                'rules' => 'required|min_length[3]|max_length[150]',
                'errors' => [
                    'required' => 'Judul cerita harus diisi',
                    'min_length' => 'Judul minimal 3 karakter',
                    'max_length' => 'Judul maksimal 150 karakter'
                ]
            ],
            'synopsis' => [
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Deskripsi cerita harus diisi',
                    'min_length' => 'Deskripsi minimal 10 karakter'
                ]
            ],
            'genre' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pilih minimal 1 genre'
                ]
            ],
            'status' => [
                'rules' => 'required|in_list[ongoing,completed,hiatus]',
                'errors' => [
                    'required' => 'Pilih status cerita',
                    'in_list' => 'Status tidak valid'
                ]
            ],
            'cover' => [
                'rules' => 'if_exist|max_size[cover,5120]|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran cover maksimal 5MB',
                    'is_image' => 'File harus berupa gambar',
                    'mime_in'  => 'Format harus JPG atau PNG',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('user_id');

        // Upload cover image
        $coverPath = null;
        $cover = $this->request->getFile('cover');

        if ($cover && $cover->isValid() && !$cover->hasMoved()) {
            if ($cover->getSize() > 5 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran file maksimal 5MB');
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($cover->getMimeType(), $allowedTypes)) {
                return redirect()->back()->withInput()->with('error', 'Format file harus JPG atau PNG');
            }

            // Pastikan folder tujuan ada
            $uploadPath = FCPATH . 'uploads/covers';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $newName = $cover->getRandomName();

            // Gunakan FCPATH agar file masuk ke public/uploads/covers/
            $cover->move($uploadPath, $newName);

            // Simpan format covers/namafile.jpg
            $coverPath = 'covers/' . $newName;

            log_message('debug', 'File uploaded: ' . $newName);
        }
        // Proses genre - gabungkan menjadi string dengan koma
        $genres = $this->request->getPost('genre');
        $genresString = implode(', ', array_map('ucfirst', $genres));

        // Mapping publication_status dari form
        $publicationStatus = $this->request->getPost('status');
        $publicationStatusMap = [
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            'hiatus' => 'On Hiatus'
        ];

        // Status sistem - bisa DRAFT atau PUBLISHED
        $isDraft = $this->request->getPost('save_draft') ? true : false;
        $systemStatus = $isDraft ? 'DRAFT' : 'PUBLISHED';

        // Data story
        $storyData = [
            'title'              => $this->request->getPost('title'),
            'author_id'          => $userId,
            'description'        => $this->request->getPost('synopsis'),
            'cover_image'        => $coverPath,
            'genres'             => $genresString,
            'status'             => $systemStatus,
            'publication_status' => $publicationStatusMap[$publicationStatus] ?? 'Ongoing',
        ];

        // Gunakan transaksi database
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Insert ke tabel stories
            $storyId = $this->storyModel->insert($storyData);

            if (!$storyId) {
                throw new \Exception('Gagal menyimpan cerita');
            }

            // Proses chapter - form mengirim array (chapter-title[], chapter-content[])
            $chapterContents = $this->request->getPost('chapter-content');
            $chapterTitles   = $this->request->getPost('chapter-title');

            // Normalisasi ke array
            if (!is_array($chapterContents)) {
                $chapterContents = $chapterContents ? [$chapterContents] : [];
            }
            if (!is_array($chapterTitles)) {
                $chapterTitles = $chapterTitles ? [$chapterTitles] : [];
            }

            foreach ($chapterContents as $index => $chapterContent) {
                if (empty(trim($chapterContent))) {
                    continue; // Skip chapter kosong
                }

                $chapterTitle = isset($chapterTitles[$index]) && !empty(trim($chapterTitles[$index]))
                    ? trim($chapterTitles[$index])
                    : 'Chapter ' . ($index + 1);

                $chapterData = [
                    'story_id'       => $storyId,
                    'title'          => $chapterTitle,
                    'content'        => $chapterContent,
                    'is_premium'     => 0,
                    'status'         => $systemStatus,
                    'chapter_number' => $index + 1,
                ];

                if (!$this->chapterModel->insert($chapterData)) {
                    throw new \Exception('Gagal menyimpan chapter ' . ($index + 1));
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan data');
            }

            // Hapus file cover jika upload gagal
            if ($coverPath && !$db->transStatus()) {
                if (file_exists($coverPath)) {
                    unlink($coverPath);
                }
            }

            // Pesan sukses
            if ($isDraft) {
                return redirect()->to('/my-stories')->with('success', 'Cerita berhasil disimpan sebagai draft');
            } else {
                return redirect()->to('/my-stories')->with('success', 'Cerita berhasil dipublikasikan!');
            }

        } catch (\Exception $e) {
            $db->transRollback();

            if ($coverPath && file_exists(FCPATH . $coverPath)) {
                unlink(FCPATH . $coverPath);
            }

            log_message('error', 'Error saving story: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan cerita: ' . $e->getMessage());
        }
    }

    /**
     * Edit story
     */
    public function edit($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $story = $this->storyModel->find($id);

        if (!$story) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Story not found');
        }

        if ($story['author_id'] != session()->get('user_id')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke cerita ini');
        }

        $story['genres_array'] = array_map('trim', explode(',', $story['genres']));

        // Ambil semua chapter (published + draft) diurutkan by chapter_number
        $allChapters = $this->chapterModel
            ->where('story_id', $id)
            ->orderBy('chapter_number', 'ASC')
            ->findAll();

        $data = [
            'title'      => 'Edit Cerita',
            'story'      => $story,
            'chapters'   => $allChapters,
            'validation' => \Config\Services::validation(),
        ];

        return view('pages/story/edit', $data);
    }
    /**
     * Update story
     */
    /**
     * Update story detail saja (tidak termasuk chapter)
     */
    public function update($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $story = $this->storyModel->find($id);

        if (!$story) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Story not found');
        }

        if ($story['author_id'] != session()->get('user_id')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke cerita ini');
        }

        $rules = [
            'title'              => 'required|min_length[3]|max_length[150]',
            'synopsis'           => 'required|min_length[10]',
            'genre'              => 'required',
            'publication_status' => 'required|in_list[Ongoing,Completed,On Hiatus]',
            'cover'              => [
                'rules'  => 'if_exist|max_size[cover,5120]|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran cover maksimal 5MB',
                    'is_image' => 'File harus berupa gambar',
                    'mime_in'  => 'Format harus JPG atau PNG',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $genres      = $this->request->getPost('genre');
        $genresString = implode(', ', array_map('ucfirst', $genres));

        $updateData = [
            'title'              => $this->request->getPost('title'),
            'description'        => $this->request->getPost('synopsis'),
            'genres'             => $genresString,
            'publication_status' => $this->request->getPost('publication_status'),
        ];

        $cover = $this->request->getFile('cover');
        if ($cover && $cover->isValid() && !$cover->hasMoved()) {
            if ($cover->getSize() > 5 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran file maksimal 5MB');
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($cover->getMimeType(), $allowedTypes)) {
                return redirect()->back()->withInput()->with('error', 'Format file harus JPG atau PNG');
            }

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
            // Balik ke tab detail setelah update
            return redirect()->to('/story/edit/' . $id . '?tab=detail')->with('success', 'Detail cerita berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui cerita');
    }

    /**
     * Daftar story user
     */
    public function myStories()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId    = session()->get('user_id');
        $allStories = $this->storyModel->where('author_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $published = [];
        $drafts    = [];

        $pubStatusBadge = [
            'Ongoing'   => ['color' => 'green',  'text' => 'Ongoing'],
            'Completed' => ['color' => 'blue',   'text' => 'Completed'],
            'On Hiatus' => ['color' => 'yellow', 'text' => 'Hiatus'],
        ];

        foreach ($allStories as &$story) {
            $story['publication_badge'] = $pubStatusBadge[$story['publication_status']] ?? ['color' => 'gray', 'text' => $story['publication_status']];
            if ($story['status'] === 'PUBLISHED') {
                $published[] = $story;
            } else {
                $drafts[] = $story;
            }
        }

        $data = [
            'title'     => 'Cerita Saya',
            'published' => $published,
            'drafts'    => $drafts,
        ];

        return view('pages/user/my-stories', $data);
    }

    /**
     * Detail story
     */
    public function detail($id)
    {
        $story = $this->storyModel->getStoryWithDetails($id);

        if (!$story) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Story not found');
        }

        // Get primary genre for related stories
        $genres = array_filter(array_map('trim', explode(',', $story['genres'] ?? '')));
        $primary_genre = !empty($genres) ? $genres[0] : '';

        // Map publication status untuk badge
        $publicationStatusBadge = [
            'Ongoing' => ['color' => 'green', 'text' => 'Ongoing'],
            'Completed' => ['color' => 'blue', 'text' => 'Completed'],
            'On Hiatus' => ['color' => 'yellow', 'text' => 'On Hiatus']
        ];

        $data = [
            'title' => $story['title'],
            'story' => $story,
            'publication_status_badge' => $publicationStatusBadge[$story['publication_status']] ?? ['color' => 'gray', 'text' => 'Unknown'],
            'chapters' => $this->chapterModel->getChaptersByStory($id),
            'reviews' => $this->reviewModel->getReviewsByStory($id, 3),
            'related_stories' => !empty($primary_genre) ? $this->storyModel->getStoriesByGenre($primary_genre, 6) : [],
            'is_bookmarked' => false,
            'chapter_count' => $this->chapterModel->getChapterCountPerStory($id),
        ];

        // Check if user has bookmarked
        if (session()->get('isLoggedIn')) {
            $userId = session()->get('user_id');
            $data['is_bookmarked'] = $this->libraryModel->isInLibrary($userId, $id);
            $data['user_rating'] = null; // RatingModel removed, handle rating via reviews
            $data['user_progress'] = $this->libraryModel->getProgress($userId, $id);
        }

        return view('pages/story/detail', $data);
    }

    /**
     * Discover story
     */
    public function discover()
    {
        $genre = $this->request->getGet('genre');
        $search = $this->request->getGet('q');

        // Get featured stories for hero section
        $featured_stories = $this->storyModel->getFeaturedStories(3);

        // Get top picks this week for main display
        $stories = $this->storyModel->getTopStoriesThisWeek(10);

        // If there's a search or genre filter, override with specific results
        if ($search) {
            $stories = $this->storyModel->searchStories($search);
        } elseif ($genre) {
            $stories = $this->storyModel->getStoriesByGenre($genre);
        }

        // Fetch trending stories by specific genres
        $trending_genres = ['Romance', 'Mystery', 'Fantasy'];
        $trending_data = [];
        foreach ($trending_genres as $genre_name) {
            $trending_data[$genre_name] = $this->storyModel->getStoriesByGenre($genre_name, 3);
        }

        // Fetch latest releases
        $latest_releases = $this->storyModel->getLatestReleases(3);

        // Fetch popular authors
        $popular_authors = $this->userModel->getPopularAuthors(6);

        $data = [
            'title' => 'Discover Stories',
            'stories' => $stories,
            'featured_stories' => $featured_stories,
            'current_genre' => $genre,
            'search_query' => $search,
            'trending_genres' => $trending_genres,
            'trending_data' => $trending_data,
            'latest_releases' => $latest_releases,
            'popular_authors' => $popular_authors,
        ];

        return view('pages/discover', $data);
    }

    /**
     * Tambah ke library
     */
    public function addToLibrary($id)
    {
        if (!session()->get('isLoggedIn')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please login first']);
            }
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');

        if ($this->libraryModel->addToLibrary($userId, $id)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Story added to library']);
            }
            return redirect()->back()->with('success', 'Cerita berhasil ditambahkan ke library');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Story already in your library']);
        }
        return redirect()->back()->with('error', 'Cerita sudah ada di library Anda');
    }

    /**
     * Hapus dari library
     */
    public function removeFromLibrary($id)
    {
        if (!session()->get('isLoggedIn')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please login first']);
            }
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        if ($this->libraryModel->removeFromLibrary($userId, $id)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Story removed from library']);
            }
            return redirect()->back()->with('success', 'Cerita dihapus dari library');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to remove story from library']);
        }
        return redirect()->back()->with('error', 'Gagal menghapus cerita dari library');
    }

    /**
     * Beri rating
     */
    public function rate($id)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Login required']);
        }

        $rating = $this->request->getPost('rating');
        $userId = session()->get('user_id');

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal menyimpan rating']);
    }

    /**
     * Hapus story milik user
     */
    public function delete($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $story = $this->storyModel->find($id);

        if (!$story) {
            return redirect()->to('/my-stories')->with('error', 'Cerita tidak ditemukan');
        }

        // Pastikan hanya pemilik yang bisa hapus
        if ($story['author_id'] != session()->get('user_id')) {
            return redirect()->to('/my-stories')->with('error', 'Anda tidak memiliki akses untuk menghapus cerita ini');
        }

        // Hapus file cover jika ada
        if ($story['cover_image'] && file_exists(FCPATH . 'uploads/' . $story['cover_image'])) {
            unlink(FCPATH . 'uploads/' . $story['cover_image']);
        }

        // Hapus semua chapter terkait
        $this->chapterModel->where('story_id', $id)->delete();

        // Hapus story
        if ($this->storyModel->delete($id)) {
            return redirect()->to('/my-stories')->with('success', 'Cerita berhasil dihapus');
        }

        return redirect()->to('/my-stories')->with('error', 'Gagal menghapus cerita');
    }
    /**
     * Halaman semua cerita
     */
    public function allStories()
    {
        $stories = $this->storyModel->getAllStories();
        $data = [
            'title' => 'Semua Cerita',
            'stories' => $stories
        ];
        return view('pages/story/all-stories', $data);
    }
}