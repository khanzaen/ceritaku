<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReportStoryModel;
use App\Models\StoryModel;

class ReportManagementController extends BaseController
{
    protected $reportModel;
    protected $storyModel;

    public function __construct()
    {
        $this->reportModel = new ReportStoryModel();
        $this->storyModel  = new StoryModel();
    }

    private function checkAdmin()
    {
        if (!session()->get('isLoggedIn') || strtoupper(session()->get('user_role')) !== 'ADMIN') {
            return redirect()->to('/')->with('error', 'Akses ditolak');
        }
        return null;
    }

    /**
     * List all reports
     */
    public function index()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        $reports = $db->table('report_story')
            ->select('
                report_story.*,
                reporter.name as reporter_name,
                reporter.profile_photo as reporter_photo,
                reporter.email as reporter_email,
                stories.title as story_title,
                stories.cover_image as story_cover,
                stories.status as story_status,
                stories.id as story_id,
                author.name as author_name
            ')
            ->join('users as reporter', 'reporter.id = report_story.user_id', 'left')
            ->join('stories', 'stories.id = report_story.story_id', 'left')
            ->join('users as author', 'author.id = stories.author_id', 'left')
            ->orderBy('report_story.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'   => 'Report Management',
            'reports' => $reports,
        ];

        return view('admin/report-story/index', $data);
    }

    /**
     * Get report detail (JSON for slide-over)
     */
    public function detail($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        $report = $db->table('report_story')
            ->select('
                report_story.*,
                reporter.name as reporter_name,
                reporter.email as reporter_email,
                reporter.profile_photo as reporter_photo,
                stories.title as story_title,
                stories.cover_image as story_cover,
                stories.status as story_status,
                stories.id as story_id,
                author.name as author_name,
                author.email as author_email
            ')
            ->join('users as reporter', 'reporter.id = report_story.user_id', 'left')
            ->join('stories', 'stories.id = report_story.story_id', 'left')
            ->join('users as author', 'author.id = stories.author_id', 'left')
            ->where('report_story.id', $id)
            ->get()
            ->getRowArray();

        if (!$report) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Report not found']);
        }

        return $this->response->setJSON($report);
    }

    /**
     * Update report status & admin note
     */
    public function update($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $status    = $this->request->getPost('status');
        $adminNote = $this->request->getPost('admin_note');

        $allowedStatus = ['pending', 'reviewed', 'resolved', 'dismissed'];
        if (!in_array($status, $allowedStatus)) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $updateData = ['status' => $status];
        if ($adminNote !== null) {
            $updateData['admin_note'] = $adminNote;
        }

        if ($this->reportModel->update($id, $updateData)) {
            return redirect()->to(base_url('/admin/reports'))->with('success', 'Report berhasil diperbarui.');
        }
        return redirect()->back()->with('error', 'Gagal memperbarui report.');
    }

    /**
     * Quick resolve — mark as resolved
     */
    public function resolve($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->reportModel->update($id, ['status' => 'resolved'])) {
            return redirect()->back()->with('success', 'Report ditandai sebagai resolved');
        }
        return redirect()->back()->with('error', 'Gagal mengubah status report');
    }

    /**
     * Quick dismiss
     */
    public function dismiss($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->reportModel->update($id, ['status' => 'dismissed'])) {
            return redirect()->back()->with('success', 'Report ditolak (dismissed)');
        }
        return redirect()->back()->with('error', 'Gagal menolak report');
    }

    /**
     * Delete report
     */
    public function delete($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        if ($this->reportModel->delete($id)) {
            return redirect()->back()->with('success', 'Report berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Gagal menghapus report');
    }
}
