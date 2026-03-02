<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StoryModel;
use App\Models\UserModel;
use App\Models\ReviewModel;
use App\Models\ChapterModel;
use App\Models\CommentModel;
use App\Models\ReportStoryModel;

class Dashboard extends BaseController
{
    protected $storyModel;
    protected $userModel;
    protected $reviewModel;
    protected $chapterModel;
    protected $commentModel;
    protected $reportModel;

    public function __construct()
    {
        $this->storyModel   = new StoryModel();
        $this->userModel    = new UserModel();
        $this->reviewModel  = new ReviewModel();
        $this->chapterModel = new ChapterModel();
        $this->commentModel = new CommentModel();
        $this->reportModel  = new ReportStoryModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn') || strtoupper(session()->get('user_role')) !== 'ADMIN') {
            return redirect()->to('/')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
        }

        $db = \Config\Database::connect();

        // Stat Cards
        $totalUsers     = $this->userModel->countAll();
        $totalStories   = $this->storyModel->countAll();
        $totalPublished = $this->storyModel->where('status', 'PUBLISHED')->countAllResults();
        $totalPending   = $this->storyModel->where('status', 'PENDING_REVIEW')->countAllResults();
        $totalReports   = $this->reportModel->countAll();
        $totalChapters  = $this->chapterModel->countAll();

        // Chart 1: User registrations per month (last 6 months)
        $userGrowth = $db->query("
            SELECT DATE_FORMAT(created_at, '%b %Y') as month,
                   DATE_FORMAT(created_at, '%Y-%m') as sort_key,
                   COUNT(*) as total
            FROM users
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY sort_key, month
            ORDER BY sort_key ASC
        ")->getResultArray();

        // Chart 2: Stories by status (donut)
        $storiesByStatus = $db->query("
            SELECT status, COUNT(*) as total
            FROM stories
            GROUP BY status
        ")->getResultArray();

        // Chart 3: Stories by genre (split comma-separated genres)
        $allGenres = $db->query("SELECT genres FROM stories WHERE status = 'PUBLISHED'")->getResultArray();
        $genreCount = [];
        foreach ($allGenres as $row) {
            $parts = explode(',', $row['genres']);
            foreach ($parts as $g) {
                $g = trim($g);
                if ($g === '') continue;
                $genreCount[$g] = ($genreCount[$g] ?? 0) + 1;
            }
        }
        arsort($genreCount);
        $genreCount = array_slice($genreCount, 0, 8, true);
        $storiesByGenre = array_map(fn($k, $v) => ['genres' => $k, 'total' => $v], array_keys($genreCount), $genreCount);

        // Chart 4: Reports per month (last 6 months)
        $reportGrowth = $db->query("
            SELECT DATE_FORMAT(created_at, '%b %Y') as month,
                   DATE_FORMAT(created_at, '%Y-%m') as sort_key,
                   COUNT(*) as total
            FROM report_story
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY sort_key, month
            ORDER BY sort_key ASC
        ")->getResultArray();

        // Latest reports with story cover and user photo
        $latestReports = $db->query("
            SELECT rs.id, rs.report_reason, rs.status, rs.created_at,
                   s.title as story_title, s.cover_image as story_cover,
                   u.name as user_name, u.profile_photo as user_photo
            FROM report_story rs
            JOIN stories s ON s.id = rs.story_id
            JOIN users u ON u.id = rs.user_id
            ORDER BY rs.created_at DESC
            LIMIT 5
        ")->getResultArray();

        // Chart 5: Top 5 stories by chapter count
        $topStories = $db->query("
            SELECT s.title, COUNT(c.id) as chapter_count
            FROM stories s
            LEFT JOIN chapters c ON c.story_id = s.id
            WHERE s.status = 'PUBLISHED'
            GROUP BY s.id, s.title
            ORDER BY chapter_count DESC
            LIMIT 5
        ")->getResultArray();

        $latestStories  = $this->storyModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
        $latestUsers    = $this->userModel->orderBy('created_at', 'DESC')->limit(5)->findAll();

        $data = [
            'title'                => 'Admin Dashboard',
            'total_users'          => $totalUsers,
            'total_stories'        => $totalStories,
            'total_published'      => $totalPublished,
            'total_pending'        => $totalPending,
            'total_reports'        => $totalReports,
            'total_chapters'       => $totalChapters,
            'latest_stories'       => $latestStories,
            'latest_users'         => $latestUsers,
            'latest_reports'       => $latestReports,
            'chart_user_growth'    => json_encode($userGrowth),
            'chart_stories_status' => json_encode($storiesByStatus),
            'chart_stories_genre'  => json_encode($storiesByGenre),
            'chart_report_growth'  => json_encode($reportGrowth),
            'chart_top_stories'    => json_encode($topStories),
        ];

        return view('admin/dashboard', $data);
    }
}