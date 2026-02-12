<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserLibrarySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 10,
                'user_id' => 11,
                'story_id' => 3,
                'progress' => 0,
                'is_reading' => 1,
                'added_at' => '2026-02-01 16:15:25',
                'updated_at' => '2026-02-01 16:15:25',
            ],
        ];

        $this->db->table('user_library')->insertBatch($data);
    }
}
