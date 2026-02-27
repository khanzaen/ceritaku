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
            return redirect()->to('/login')->with('error', 'Please log in to create a story');
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
            return redirect()->to('/login')->with('error', 'Please log in first');
        }

        // Validasi input
        // Catatan: publication_status TIDAK divalidasi di sini karena hanya relevan
        // setelah cerita diterbitkan (PUBLISHED). Saat create, nilainya di-default ke 'Ongoing'.
        $rules = [
            'title' => [
                'rules' => 'required|min_length[3]|max_length[150]',
                'errors' => [
                    'required'    => 'Judul cerita harus diisi',
                    'min_length'  => 'Judul minimal 3 karakter',
                    'max_length'  => 'Judul maksimal 150 karakter',
                ]
            ],
            'synopsis' => [
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required'   => 'Deskripsi cerita harus diisi',
                    'min_length' => 'Deskripsi minimal 10 karakter',
                ]
            ],
            'genre' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pilih minimal 1 genre',
                ]
            ],
            // status hanya boleh DRAFT atau PENDING_REVIEW saat user membuat cerita.
            // PUBLISHED dan ARCHIVED hanya bisa di-set oleh admin.
            'status' => [
                'rules' => 'required|in_list[DRAFT,PENDING_REVIEW]',
                'errors' => [
                    'required' => 'Pilih aksi simpan cerita',
                    'in_list'  => 'Aksi tidak valid. Pilih "Simpan Draft" atau "Kirim untuk Review"',
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
                return redirect()->back()->withInput()->with('error', 'File size must not exceed 5MB');
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($cover->getMimeType(), $allowedTypes)) {
                return redirect()->back()->withInput()->with('error', 'File format must be JPG or PNG');
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

        // ── STATUS (field sistem) ─────────────────────────────────────────────
        // Diambil dari hidden input <input name="status"> yang di-set via JS
        // berdasarkan tombol yang diklik: "DRAFT" atau "PENDING_REVIEW".
        // Sudah divalidasi in_list[DRAFT,PENDING_REVIEW] di atas.
        $systemStatus = $this->request->getPost('status'); // 'DRAFT' | 'PENDING_REVIEW'
        $isDraft = ($systemStatus === 'DRAFT');

        // ── PUBLICATION STATUS (field konten) ────────────────────────────────
        // publication_status (Ongoing/Completed/On Hiatus) hanya bermakna setelah
        // cerita berstatus PUBLISHED. Saat create, selalu di-default ke 'Ongoing'.
        // Penulis dapat mengubahnya lewat halaman edit setelah cerita diterbitkan.
        $publicationStatus = 'Ongoing';

        // Data story
        $storyData = [
            'title'              => $this->request->getPost('title'),
            'author_id'          => $userId,
            'description'        => $this->request->getPost('synopsis'),
            'cover_image'        => $coverPath,
            'genres'             => $genresString,
            'status'             => $systemStatus,      // DRAFT | PENDING_REVIEW
            'publication_status' => $publicationStatus, // selalu 'Ongoing' saat create
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
                    throw new \Exception('Failed to save chapter ' . ($index + 1));
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

            // Pesan sukses sesuai status yang dipilih
            if ($isDraft) {
                return redirect()->to('/my-stories')->with('success', 'Story saved as draft. You can edit it anytime.');
            } else {
                return redirect()->to('/my-stories')->with('success', 'Story submitted for review. Admin will check and publish it shortly.');
            }

        } catch (\Exception $e) {
            $db->transRollback();

            if ($coverPath && file_exists(FCPATH . $coverPath)) {
                unlink(FCPATH . $coverPath);
            }

            log_message('error', 'Error saving story: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to save story: ' . $e->getMessage());
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
            return redirect()->back()->with('error', 'You do not have access to this story');
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

        // Pastikan hanya pemilik yang bisa edit (status diatur admin via panel terpisah)
        if ($story['author_id'] != session()->get('user_id')) {
            return redirect()->back()->with('error', 'You do not have access to this story');
        }

        // ── VALIDASI ──────────────────────────────────────────────────────────
        // Catatan: field `status` TIDAK ada di form edit penulis — status cerita
        // diatur oleh admin. Penulis hanya mengubah konten + publication_status
        // (dan publication_status hanya ditampilkan jika cerita sudah PUBLISHED).
        $rules = [
            'title'    => 'required|min_length[3]|max_length[150]',
            'synopsis' => 'required|min_length[10]',
            'genre'    => 'required',
            // publication_status hanya wajib jika cerita sudah PUBLISHED
            'publication_status' => [
                'rules'  => 'if_exist|in_list[Ongoing,Completed,On Hiatus]',
                'errors' => ['in_list' => 'Status publikasi tidak valid'],
            ],
            'cover' => [
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

        $genres       = $this->request->getPost('genre');
        $genresString = implode(', ', array_map('ucfirst', $genres));

        $updateData = [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('synopsis'),
            'genres'      => $genresString,
            // status TIDAK diubah oleh penulis — hanya admin yang bisa mengubahnya
        ];

        // ── PUBLICATION STATUS ────────────────────────────────────────────────
        // Hanya proses publication_status jika cerita sudah PUBLISHED.
        // Jika masih DRAFT/PENDING_REVIEW, field ini diabaikan meski dikirim.
        if ($story['status'] === 'PUBLISHED') {
            $pubStatus = $this->request->getPost('publication_status');
            $allowedPubStatus = ['Ongoing', 'Completed', 'On Hiatus'];
            if ($pubStatus && in_array($pubStatus, $allowedPubStatus)) {
                $updateData['publication_status'] = $pubStatus;
            }
        }

        $cover = $this->request->getFile('cover');
        if ($cover && $cover->isValid() && !$cover->hasMoved()) {
            if ($cover->getSize() > 5 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'File size must not exceed 5MB');
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($cover->getMimeType(), $allowedTypes)) {
                return redirect()->back()->withInput()->with('error', 'File format must be JPG or PNG');
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
            return redirect()->to('/story/edit/' . $id . '?tab=detail')->with('success', 'Story details updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update story');
    }

    /**
     * Submit story for review (DRAFT → PENDING_REVIEW)
     * atau re-submit jika sudah PUBLISHED (tetap PENDING_REVIEW untuk re-review)
     */
    public function submitForReview($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $story = $this->storyModel->find($id);

        if (!$story) {
            return redirect()->to('/my-stories')->with('error', 'Story not found');
        }

        if ($story['author_id'] != session()->get('user_id')) {
            return redirect()->to('/my-stories')->with('error', 'You do not have access to this story');
        }

        // Hanya boleh submit jika DRAFT, PENDING_REVIEW, atau PUBLISHED (re-submit update)
        $allowedStatuses = ['DRAFT', 'PENDING_REVIEW', 'PUBLISHED'];
        if (!in_array($story['status'], $allowedStatuses)) {
            return redirect()->back()->with('error', 'Story cannot be submitted for review at this time');
        }

        $isResubmit = in_array($story['status'], ['PENDING_REVIEW', 'PUBLISHED']);

        // Update status story ke PENDING_REVIEW
        $this->storyModel->update($id, ['status' => 'PENDING_REVIEW']);

        // Semua chapter (DRAFT / PUBLISHED) ikut di-set PENDING_REVIEW
        // Chapter yang sudah PUBLISHED tidak perlu di-review ulang secara terpisah —
        // admin meninjau story sebagai satu kesatuan.
        $this->chapterModel
            ->whereIn('status', ['DRAFT', 'PUBLISHED'])
            ->where('story_id', $id)
            ->set(['status' => 'PENDING_REVIEW'])
            ->update();

        $msg = $isResubmit
            ? 'Story & all chapters resubmitted for review. Admin will check your updates.'
            : 'Story & all chapters submitted for review. Admin will check and publish them shortly.';

        return redirect()->to('/story/edit/' . $id . '?tab=detail')->with('review', $msg);
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
            // publication_badge hanya relevan untuk cerita PUBLISHED
            // Untuk cerita non-published, set badge kosong agar view tidak error
            if ($story['status'] === 'PUBLISHED') {
                $story['publication_badge'] = $pubStatusBadge[$story['publication_status']] ?? ['color' => 'gray', 'text' => $story['publication_status']];
                $published[] = $story;
            } else {
                $story['publication_badge'] = ['color' => 'gray', 'text' => ''];
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
            return redirect()->to('/login')->with('error', 'Please log in first');
        }

        $userId = session()->get('user_id');

        if ($this->libraryModel->addToLibrary($userId, $id)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Story added to library']);
            }
            return redirect()->back()->with('success', 'Story added to library');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Story already in your library']);
        }
        return redirect()->back()->with('error', 'Story is already in your library');
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
            return redirect()->back()->with('success', 'Story removed from library');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to remove story from library']);
        }
        return redirect()->back()->with('error', 'Failed to delete story dari library');
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
            return redirect()->to('/my-stories')->with('error', 'Story not found');
        }

        // Pastikan hanya pemilik yang bisa hapus
        if ($story['author_id'] != session()->get('user_id')) {
            return redirect()->to('/my-stories')->with('error', 'You do not have permission to delete this story');
        }

        // Hapus file cover jika ada
        if ($story['cover_image'] && file_exists(FCPATH . 'uploads/' . $story['cover_image'])) {
            unlink(FCPATH . 'uploads/' . $story['cover_image']);
        }

        // Hapus semua chapter terkait
        $this->chapterModel->where('story_id', $id)->delete();

        // Hapus story
        if ($this->storyModel->delete($id)) {
            return redirect()->to('/my-stories')->with('success', 'Story deleted successfully');
        }

        return redirect()->to('/my-stories')->with('error', 'Failed to delete story');
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