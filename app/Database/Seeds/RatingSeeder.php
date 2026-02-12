<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        
        $data = [
            ['id' => 1, 'story_id' => 1, 'user_id' => 1, 'rating' => 5, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 2, 'story_id' => 1, 'user_id' => 2, 'rating' => 5, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 3, 'story_id' => 1, 'user_id' => 5, 'rating' => 4, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 4, 'story_id' => 2, 'user_id' => 1, 'rating' => 4, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 5, 'story_id' => 2, 'user_id' => 2, 'rating' => 5, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 6, 'story_id' => 2, 'user_id' => 5, 'rating' => 4, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 7, 'story_id' => 3, 'user_id' => 1, 'rating' => 4, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 8, 'story_id' => 3, 'user_id' => 2, 'rating' => 4, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 9, 'story_id' => 3, 'user_id' => 5, 'rating' => 5, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 10, 'story_id' => 4, 'user_id' => 1, 'rating' => 5, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 11, 'story_id' => 4, 'user_id' => 2, 'rating' => 4, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 12, 'story_id' => 4, 'user_id' => 5, 'rating' => 5, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 13, 'story_id' => 5, 'user_id' => 1, 'rating' => 4, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 14, 'story_id' => 5, 'user_id' => 2, 'rating' => 4, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 15, 'story_id' => 5, 'user_id' => 5, 'rating' => 3, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 16, 'story_id' => 6, 'user_id' => 1, 'rating' => 5, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 17, 'story_id' => 6, 'user_id' => 2, 'rating' => 5, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 18, 'story_id' => 6, 'user_id' => 5, 'rating' => 4, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 22, 'story_id' => 8, 'user_id' => 1, 'rating' => 5, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 23, 'story_id' => 8, 'user_id' => 2, 'rating' => 4, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 24, 'story_id' => 8, 'user_id' => 5, 'rating' => 5, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 25, 'story_id' => 9, 'user_id' => 1, 'rating' => 5, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 26, 'story_id' => 9, 'user_id' => 2, 'rating' => 4, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
            ['id' => 27, 'story_id' => 9, 'user_id' => 5, 'rating' => 5, 'created_at' => '2026-01-28 13:11:03', 'updated_at' => '2026-01-28 13:11:03'],
        ];

        // Generate 30 random ratings dengan Faker
        // IDs yang ada dalam Stories: 1, 2, 3, 4, 5, 6, 8, 9 (7 tidak ada!)
        $availableStoryIds = [1, 2, 3, 4, 5, 6, 8, 9];
        
        $startId = 28;
        $usedCombinations = [
            [1, 1], [1, 2], [1, 5],
            [2, 1], [2, 2], [2, 5],
            [3, 1], [3, 2], [3, 5],
            [4, 1], [4, 2], [4, 5],
            [5, 1], [5, 2], [5, 5],
            [6, 1], [6, 2], [6, 5],
            [8, 1], [8, 2], [8, 5],
            [9, 1], [9, 2], [9, 5],
        ];

        for ($i = 0; $i < 30; $i++) {
            do {
                $storyId = $faker->randomElement($availableStoryIds);
                $userId = $faker->numberBetween(3, 21);
                $combination = [$storyId, $userId];
            } while (in_array($combination, $usedCombinations));
            
            $usedCombinations[] = $combination;
            
            $data[] = [
                'id' => $startId + $i,
                'story_id' => $storyId,
                'user_id' => $userId,
                'rating' => $faker->numberBetween(3, 5),
                'created_at' => $faker->dateTimeBetween('-2 months')->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTimeBetween('-2 months')->format('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('ratings')->insertBatch($data);
    }
}
