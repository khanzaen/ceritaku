<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class UserLibraryController extends Controller
{
    /**
     * Show user's library page
     */
    public function myLibrary()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/')->with('error', 'Silakan login untuk melihat library');
        }
        $libraryModel = new \App\Models\UserLibraryModel();
        $library = $libraryModel->getUserLibrary($userId);
        // Inject progress_percent for each item
        foreach ($library as &$item) {
            $item['progress_percent'] = $libraryModel->getProgressPercent($userId, $item['story_id']);
            // description sudah otomatis dari getUserLibrary
        }
        unset($item);
        return view('pages/my-library', [
            'title' => 'My Library',
            'library' => $library
        ]);
    }
}

    