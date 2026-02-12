<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        
        $data = [
            ['id' => 31, 'story_id' => 1, 'user_id' => 1, 'review' => 'Novel ini bukan sekadar cerita, tapi pengalaman emosional. Setiap halaman membuat saya ikut merasakan harapan, ketakutan, dan kehilangan para tokohnya. Sangat kuat dan membekas.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 32, 'story_id' => 1, 'user_id' => 2, 'review' => 'Cerita yang membuka mata tentang sisi gelap sejarah, disampaikan dengan bahasa yang indah dan menyayat. Setelah selesai membaca, rasanya sulit untuk langsung move on.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 33, 'story_id' => 1, 'user_id' => 5, 'review' => 'Laut Bercerita berhasil membuat saya terdiam lama setelah tamat. Kisah persahabatan dan perjuangannya terasa nyata dan penuh makna.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 34, 'story_id' => 2, 'user_id' => 1, 'review' => 'Ringan, lucu, dan romantis. Dialog Dilan benar-benar ikonik dan bikin senyum-senyum sendiri saat membaca.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 35, 'story_id' => 2, 'user_id' => 2, 'review' => 'Cerita cinta remaja yang sederhana tapi jujur. Membaca novel ini seperti mengulang kenangan masa SMA.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 36, 'story_id' => 2, 'user_id' => 5, 'review' => 'Dilan 1990 cocok dibaca kapan saja. Alurnya santai, karakternya unik, dan romancenya terasa hangat.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 37, 'story_id' => 3, 'user_id' => 1, 'review' => 'Novel ini terasa sangat relate. Tentang luka, bertahan, dan belajar menerima diri sendiri ketika cinta tidak berjalan seperti harapan.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 38, 'story_id' => 3, 'user_id' => 2, 'review' => 'Cerita yang emosional dan menyentuh hati. Banyak bagian yang membuat saya merenung tentang hubungan dan perasaan.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 39, 'story_id' => 3, 'user_id' => 5, 'review' => 'Bahasanya lembut namun dalam. Cocok untuk pembaca yang suka cerita romance dengan konflik batin yang kuat.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 40, 'story_id' => 4, 'user_id' => 1, 'review' => 'Cerita yang sederhana tapi sangat dalam. Banyak kalimat yang membuat saya berhenti sejenak untuk berpikir tentang hidup.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 41, 'story_id' => 4, 'user_id' => 2, 'review' => 'Novel ini unik dan reflektif. Dari hal sederhana seperti semangkuk mie ayam, ceritanya berkembang menjadi renungan kehidupan.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 42, 'story_id' => 4, 'user_id' => 5, 'review' => 'Bacaan yang tenang namun menusuk. Cocok dibaca saat ingin menyendiri dan merenung.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 43, 'story_id' => 5, 'user_id' => 1, 'review' => 'Sudut pandang Dilan membuat cerita ini terasa lebih emosional dan dewasa. Banyak perasaan yang akhirnya terjawab.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 44, 'story_id' => 5, 'user_id' => 2, 'review' => 'Milea memberikan sudut pandang yang berbeda dari kisah sebelumnya. Lebih serius dan penuh konflik perasaan.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
            ['id' => 45, 'story_id' => 5, 'user_id' => 5, 'review' => 'Sebagai penutup kisah Dilan dan Milea, novel ini terasa pas dan menyentuh.', 'is_featured' => 0, 'created_at' => '2026-01-27 16:53:09', 'updated_at' => '2026-01-27 16:53:09'],
        ];

        // Generate 15 random reviews dengan Faker
        $reviewTemplates = [
            'Cerita {jenis} yang sangat menarik dan berkesan. Setiap detail benar-benar dipikirkan dengan matang.',
            'Saya sangat terkesan dengan gaya penulisan yang indah dan menyentuh hati. Rekomendasi bagus!',
            'Plot dan karakternya sangat bagus. Tidak bisa berhenti membaca hingga selesai.',
            'Karya yang luar biasa! Filosofi hidup yang terkandung sangat bermakna.',
            'Emosi yang dibangun penulis benar-benar sampai ke pembaca. Masterpiece!',
            'Ending yang sempurna! Semua pertanyaan terjawab dengan memuaskan.',
            'Banyak pelajaran hidup dalam cerita ini. Saya akan membaca lagi!',
            'Karakternya relatable dan Development-nya natural. Sangat suka!',
            'Bahasa penulis yang puitis membuat cerita ini semakin indah.',
            'Klimaksnya bikin jantung berdetak cepat! Sangat tense dan menarik.',
            'Cerita yang sederhana tapi penuh makna. Inspired!',
            'Karya terbaik yang pernah saya baca tahun ini!',
            'Tidak ada yang membosankan dalam setiap halaman.',
            'Love story yang authentic dan menyentuh.',
            'Pacing cerita sangat sempurna, tidak terasa membosankan.',
        ];

        // IDs yang ada dalam Stories: 1, 2, 3, 4, 5, 6, 8, 9 (7 tidak ada!)
        $availableStoryIds = [1, 2, 3, 4, 5, 6, 8, 9];

        $startId = 46;
        for ($i = 0; $i < 15; $i++) {
            $storyId = $faker->randomElement($availableStoryIds);
            $jenis = $faker->randomElement(['drama', 'romance', 'slice of life', 'adventure', 'mystery']);
            
            $data[] = [
                'id' => $startId + $i,
                'story_id' => $storyId,
                'user_id' => $faker->numberBetween(1, 21),
                'review' => str_replace('{jenis}', $jenis, $faker->randomElement($reviewTemplates)),
                'is_featured' => $faker->boolean(20), // 20% chance featured
                'created_at' => $faker->dateTimeBetween('-2 months')->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTimeBetween('-2 months')->format('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('reviews')->insertBatch($data);
    }
}
