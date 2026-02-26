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
        $storyId     = $this->request->getPost('story_id');
        $reason      = $this->request->getPost('report_reason');
        $description = $this->request->getPost('description');
        $userId      = session()->get('user_id');

        if (!$storyId || !$reason) {
            return redirect()->back()->with('error', 'Please select a reason.');
        }

        // Handle evidence image upload
        $evidencePath = null;
        $evidence = $this->request->getFile('evidence_image');
        if ($evidence && $evidence->isValid() && !$evidence->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/evidence';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $newName = $evidence->getRandomName();
            $evidence->move($uploadPath, $newName);
            $evidencePath = 'evidence/' . $newName;
        }

        $data = [
            'story_id'       => $storyId,
            'user_id'        => $userId,
            'report_reason'  => $reason,
            'description'    => $description,
            'evidence_image' => $evidencePath,
            'status'         => 'pending',
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