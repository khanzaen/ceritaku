<?php

namespace App\Controllers;

use App\Models\ReportStoryModel;
use CodeIgniter\HTTP\RedirectResponse;

class ReportStoryController extends BaseController
{
    protected $reportModel;

    public function __construct()
    {
        $this->reportModel = new ReportStoryModel();
    }

    /**
     * Handle report story form submission
     */
    public function submit()
    {
        $storyId = $this->request->getPost('story_id');
        $reason = $this->request->getPost('reason');
        $details = $this->request->getPost('details');
        $userId = session()->get('user_id');

        if (!$storyId || !$reason) {
            return redirect()->back()->with('error', 'Please select a reason.');
        }

        $data = [
            'story_id' => $storyId,
            'user_id' => $userId,
            'reason' => $reason,
            'details' => $details,
        ];

        if ($this->reportModel->insert($data)) {
            return redirect()->back()->with('success', 'Thank you for your report.');
        } else {
            return redirect()->back()->with('error', 'Failed to submit report.');
        }
    }

    /**
     * Show report story form/modal for a specific story
     */
    public function index($storyId)
    {
        // Optionally, you can fetch story info for context
        // $story = model('StoryModel')->find($storyId);
        // return view('pages/report-story', ['story' => $story, 'story_id' => $storyId]);
        // For modal-based, just pass story_id
        return view('layouts/modals/story/report-story-modal', ['story_id' => $storyId]);
    }
}
