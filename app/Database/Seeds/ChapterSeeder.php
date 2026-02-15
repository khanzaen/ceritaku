<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ChapterSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'story_id' => 1,
            'title' => 'Bab 1: Ombak Pertama',
            'chapter_number' => 1,
            'content' => 'Laut menatap cakrawala, membiarkan angin membawa segala resahnya.',
            'is_pr emium' => 0,
            'status' => 'PUBLISHED',
            'view_count' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('chapters')->insert($data);
    }
}
