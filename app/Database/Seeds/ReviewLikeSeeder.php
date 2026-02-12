<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReviewLikeSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        
        $data = [
            ['id' => 1, 'review_id' => 31, 'user_id' => 2, 'created_at' => '2026-01-28 15:25:00'],
            ['id' => 2, 'review_id' => 31, 'user_id' => 3, 'created_at' => '2026-01-28 15:25:00'],
            ['id' => 3, 'review_id' => 31, 'user_id' => 4, 'created_at' => '2026-01-28 15:25:00'],
            ['id' => 4, 'review_id' => 34, 'user_id' => 2, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 5, 'review_id' => 34, 'user_id' => 3, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 6, 'review_id' => 34, 'user_id' => 5, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 7, 'review_id' => 35, 'user_id' => 1, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 8, 'review_id' => 35, 'user_id' => 3, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 9, 'review_id' => 35, 'user_id' => 5, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 10, 'review_id' => 36, 'user_id' => 1, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 11, 'review_id' => 36, 'user_id' => 2, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 12, 'review_id' => 36, 'user_id' => 3, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 13, 'review_id' => 37, 'user_id' => 2, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 14, 'review_id' => 37, 'user_id' => 4, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 15, 'review_id' => 37, 'user_id' => 5, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 16, 'review_id' => 38, 'user_id' => 1, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 17, 'review_id' => 38, 'user_id' => 4, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 18, 'review_id' => 38, 'user_id' => 5, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 19, 'review_id' => 39, 'user_id' => 1, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 20, 'review_id' => 39, 'user_id' => 2, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 21, 'review_id' => 39, 'user_id' => 4, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 22, 'review_id' => 40, 'user_id' => 2, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 23, 'review_id' => 40, 'user_id' => 3, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 24, 'review_id' => 40, 'user_id' => 5, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 25, 'review_id' => 41, 'user_id' => 1, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 26, 'review_id' => 41, 'user_id' => 3, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 27, 'review_id' => 41, 'user_id' => 5, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 28, 'review_id' => 42, 'user_id' => 1, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 29, 'review_id' => 42, 'user_id' => 2, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 30, 'review_id' => 42, 'user_id' => 3, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 31, 'review_id' => 43, 'user_id' => 2, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 32, 'review_id' => 43, 'user_id' => 4, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 33, 'review_id' => 43, 'user_id' => 5, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 34, 'review_id' => 44, 'user_id' => 1, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 35, 'review_id' => 44, 'user_id' => 4, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 36, 'review_id' => 44, 'user_id' => 5, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 37, 'review_id' => 45, 'user_id' => 1, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 38, 'review_id' => 45, 'user_id' => 2, 'created_at' => '2026-01-28 15:26:50'],
            ['id' => 39, 'review_id' => 45, 'user_id' => 4, 'created_at' => '2026-01-28 15:26:50'],
        ];

        // Generate 25 random review likes dengan Faker
        // Perlu hindari unique constraint pada (review_id, user_id)
        $startId = 40;
        $usedCombinations = [];
        
        // Collect existing combinations
        foreach ($data as $like) {
            $usedCombinations[] = [$like['review_id'], $like['user_id']];
        }

        for ($i = 0; $i < 25; $i++) {
            do {
                $reviewId = $faker->numberBetween(31, 60); // dari existing + faker reviews
                $userId = $faker->numberBetween(1, 21);
                $combination = [$reviewId, $userId];
            } while (in_array($combination, $usedCombinations));
            
            $usedCombinations[] = $combination;
            
            $data[] = [
                'id' => $startId + $i,
                'review_id' => $reviewId,
                'user_id' => $userId,
                'created_at' => $faker->dateTimeBetween('-1 month')->format('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('review_likes')->insertBatch($data);
    }
}
