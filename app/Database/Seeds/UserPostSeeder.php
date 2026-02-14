<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserPostSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Postingan user 7 (Pidi Baiq) - karya: Dilan, Milea
            [
                'user_id'    => 7,
                'content'    => 'Haloo, bab terbaru "Dilan 1990" sudah dipublikasi! Milea makin seru, jangan lupa baca ya.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null,
            ],
            [
                'user_id'    => 7,
                'content'    => 'Hari ini aku izin nggak update "Milea" karena lagi ada acara keluarga. Terima kasih sudah setia menunggu!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => null,
            ],
            [
                'user_id'    => 7,
                'content'    => 'Terima kasih untuk semua pembaca Dilan & Milea! Komentar kalian bikin semangat nulis.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => null,
            ],
            // Postingan penggemar ke user 7
            [
                'user_id'    => 2,
                'content'    => 'Kak @pidibaiq, bab terbaru Dilan bikin baper banget! Ditunggu lanjutannya ya!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => null,
            ],
            [
                'user_id'    => 3,
                'content'    => 'Milea versi terbaru keren, Kak! Sukses terus untuk karya-karyanya.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'updated_at' => null,
            ],
            // Postingan user lain (contoh)
            [
                'user_id'    => 6,
                'content'    => 'Bab baru "Laut Bercerita" sudah publish. Terima kasih yang sudah menunggu!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => null,
            ],
            [
                'user_id'    => 8,
                'content'    => 'Update: "Love Me When It Hurts" bab 10 sudah tayang. Siap-siap baper ya!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => null,
            ],
            [
                'user_id'    => 9,
                'content'    => 'Terima kasih untuk semua yang sudah membaca "Seporsi Mie Ayam Sebelum Mati". Komentar kalian sangat berarti!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => null,
            ],
            [
                'user_id'    => 10,
                'content'    => 'Bab baru "Hujan" sudah rilis. Jangan lupa baca dan kasih review ya!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'updated_at' => null,
            ],
            [
                'user_id'    => 1,
                'content'    => 'Hari ini aku nggak bisa update cerita karena ada urusan mendadak. Sampai jumpa di bab selanjutnya!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => null,
            ],
            [
                'user_id'    => 2,
                'content'    => 'Senang sekali bisa berbagi cerita dengan kalian semua. Terima kasih sudah membaca!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
                'updated_at' => null,
            ],
            [
                'user_id'    => 3,
                'content'    => 'Bab spesial "Petualangan di Dunia Fantasi" sudah tayang. Jangan lupa tinggalkan komentar!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'updated_at' => null,
            ],
            [
                'user_id'    => 4,
                'content'    => 'Selamat datang untuk pembaca baru! Semoga betah di platform ini.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
                'updated_at' => null,
            ],
            [
                'user_id'    => 5,
                'content'    => 'Cerita pendek terbaru sudah publish. Silakan dibaca dan beri masukan!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-9 days')),
                'updated_at' => null,
            ],
            [
                'user_id'    => 6,
                'content'    => 'Terima kasih untuk semua support di "Laut Bercerita". Kalian luar biasa!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => null,
            ],
            [
                'user_id'    => 8,
                'content'    => 'Bab baru "Love Me When It Hurts" akan tayang malam ini. Stay tuned!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-11 days')),
                'updated_at' => null,
            ],
            [
                'user_id'    => 9,
                'content'    => 'Cerita slice of life terbaru sudah bisa dibaca. Terima kasih atas dukungannya!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
                'updated_at' => null,
            ],
        ];

        // Tambahan 10 postingan random
        $randomPosts = [
            [
                'user_id' => 2,
                'content' => 'Akhirnya bisa menyelesaikan bab terakhir! Rasanya lega banget.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-13 days')),
                'updated_at' => null,
            ],
            [
                'user_id' => 3,
                'content' => 'Ada yang punya rekomendasi cerita genre misteri? Pingin baca yang seru!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-14 days')),
                'updated_at' => null,
            ],
            [
                'user_id' => 4,
                'content' => 'Senang bisa gabung di komunitas ini. Banyak inspirasi baru!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'updated_at' => null,
            ],
            [
                'user_id' => 5,
                'content' => 'Lagi suka banget sama cerita-cerita bertema keluarga. Ada saran?',
                'created_at' => date('Y-m-d H:i:s', strtotime('-16 days')),
                'updated_at' => null,
            ],
            [
                'user_id' => 6,
                'content' => 'Terima kasih sudah support karya-karya saya. Semangat menulis untuk semua!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-17 days')),
                'updated_at' => null,
            ],
            [
                'user_id' => 8,
                'content' => 'Baru saja baca cerita yang sangat menyentuh. Penulisnya keren banget!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-18 days')),
                'updated_at' => null,
            ],
            [
                'user_id' => 9,
                'content' => 'Mencoba menulis genre baru, semoga pembaca suka!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-19 days')),
                'updated_at' => null,
            ],
            [
                'user_id' => 10,
                'content' => 'Bulan ini targetnya bisa update cerita minimal 3 bab. Semangat!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
                'updated_at' => null,
            ],
            [
                'user_id' => 1,
                'content' => 'Terima kasih untuk semua feedback positifnya. Membantu banget buat berkembang.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-21 days')),
                'updated_at' => null,
            ],
            [
                'user_id' => 5,
                'content' => 'Ada yang mau kolaborasi nulis cerita bareng? DM ya!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-22 days')),
                'updated_at' => null,
            ],
        ];

        $this->db->table('user_posts')->insertBatch($data);
        $this->db->table('user_posts')->insertBatch($randomPosts);
    }
}
