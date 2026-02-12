<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        
        $data = [
            [
                'id' => 1,
                'chapter_id' => 1,
                'user_id' => 1,
                'comment' => 'Cara ceritanya dibuka pelan tapi menghantam. Rasanya seperti diajak masuk ke dunia yang penuh harapan tapi juga ancaman.',
                'created_at' => '2026-01-28 13:08:59',
                'updated_at' => '2026-01-28 13:08:59',
            ],
            [
                'id' => 2,
                'chapter_id' => 1,
                'user_id' => 2,
                'comment' => 'Aku suka detail-detail kecilnya. Obrolan sederhana tapi maknanya dalam, bikin penasaran sama kelanjutannya.',
                'created_at' => '2026-01-28 13:08:59',
                'updated_at' => '2026-01-28 13:08:59',
            ],
            [
                'id' => 3,
                'chapter_id' => 2,
                'user_id' => 1,
                'comment' => 'Chapter ini jujur bikin sesak. Kehilangan yang datang tanpa penjelasan terasa sangat nyata.',
                'created_at' => '2026-01-28 13:08:59',
                'updated_at' => '2026-01-28 13:08:59',
            ],
            [
                'id' => 4,
                'chapter_id' => 2,
                'user_id' => 5,
                'comment' => 'Bagian ini bikin aku berhenti baca sebentar. Emosinya kuat banget, terutama saat suasana mulai sunyi dan tegang.',
                'created_at' => '2026-01-28 13:08:59',
                'updated_at' => '2026-01-28 13:08:59',
            ],
        ];

        // Generate 20 random comments dengan Faker
        $commentSamples = [
            'Cerita yang luar biasa, sangat menarik!',
            'Karya terbaik yang pernah saya baca.',
            'Alurnya bagus, tapi ingin lebih detail.',
            'Karakternya relatable, saya suka sekali.',
            'Plot twist yang tidak terduga!',
            'Ending-nya membuat saya terdiam lama.',
            'Gaya penulisannya indah dan menyentuh.',
            'Bikin saya ingin lanjut membaca terus.',
            'Rekomendasi bagus untuk teman-teman.',
            'Emosi di chapter ini sangat kuat.',
            'Dialog-dialognya natural dan bagus.',
            'Worldbuilding yang detail dan matang.',
            'Karakter development yang sempurna.',
            'Pacing cerita sangat pas.',
            'Klimaks ceritanya bikin nyesek.',
            'Banyak filosofi hidup dalam cerita ini.',
            'Bahasa penulis sangat bagus dan indah.',
            'Aku tidak bisa berhenti membaca.',
            'Cerita yang bermakna dan berkesan.',
            'Penuh dengan inspiraasi untuk hidup.',
        ];

        $startId = 5;
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'id' => $startId + $i,
                'chapter_id' => $faker->randomElement([1, 2]),
                'user_id' => $faker->numberBetween(1, 11),
                'comment' => $faker->randomElement($commentSamples),
                'created_at' => $faker->dateTimeBetween('-1 month')->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTimeBetween('-1 month')->format('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('comments')->insertBatch($data);
    }
}
