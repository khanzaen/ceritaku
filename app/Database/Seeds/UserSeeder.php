<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        $bioSamples = [
            'Pecinta novel dan penikmat cerita bergenre drama.',
            'Suka membaca di waktu luang dan menulis cerita pendek.',
            'Pembaca aktif genre romance dan slice of life.',
            'Penggemar cerita fantasi dan petualangan.',
            'Suka membahas plot twist dan karakter favorit.',
            'Kolektor buku dan reviewer di komunitas baca.',
            'Menikmati cerita dengan konflik emosional yang kuat.',
            'Sering membuat ulasan singkat setelah membaca.',
            'Penggemar karya sastra Indonesia modern.',
            'Hobi membaca sambil ditemani kopi hangat.',
        ];
        
        $data = [
            [
                'id' => 1,
                'name' => 'Khanza Haura',
                'username' => 'khanza',
                'email' => 'khanza.haura.148@mail.com',
                'password' => '$2y$10$examplehashkanza1234567890',
                'role' => 'USER',
                'bio' => 'Penikmat novel dan penulis pemula.',
                'profile_photo' => 'uploads/profiles/khanza.jpg',
                'is_verified' => 1,
                'created_at' => '2026-01-24 18:40:11',
                'updated_at' => '2026-01-28 15:37:39',
            ],
            [
                'id' => 2,
                'name' => 'Alya Putri',
                'username' => 'alyap',
                'email' => 'alya@mail.com',
                'password' => '$2y$10$examplehashalya1234567890',
                'role' => 'USER',
                'bio' => 'Suka membaca novel romance.',
                'profile_photo' => 'uploads/profiles/alya.jpg',
                'is_verified' => 1,
                'created_at' => '2026-01-24 18:40:11',
                'updated_at' => '2026-01-24 18:40:11',
            ],
            [
                'id' => 3,
                'name' => 'Bima Pratama',
                'username' => 'bimap',
                'email' => 'bima@mail.com',
                'password' => '$2y$10$examplehashbima1234567890',
                'role' => 'USER',
                'bio' => 'Penulis cerita fantasi.',
                'profile_photo' => 'uploads/profiles/bima.jpg',
                'is_verified' => 0,
                'created_at' => '2026-01-24 18:40:11',
                'updated_at' => '2026-01-24 18:40:11',
            ],
            [
                'id' => 4,
                'name' => 'Admin Platform',
                'username' => 'admin',
                'email' => 'admin@mail.com',
                'password' => '$2y$10$examplehashadmin1234567890',
                'role' => 'ADMIN',
                'bio' => 'Administrator platform novel.',
                'profile_photo' => 'uploads/profiles/admin.jpg',
                'is_verified' => 1,
                'created_at' => '2026-01-24 18:40:11',
                'updated_at' => '2026-01-24 18:40:11',
            ],
            [
                'id' => 5,
                'name' => 'Dewi Lestari',
                'username' => 'dewil',
                'email' => 'dewi@mail.com',
                'password' => '$2y$10$examplehashdewi1234567890',
                'role' => 'USER',
                'bio' => 'Pembaca aktif dan reviewer.',
                'profile_photo' => 'uploads/profiles/dewi.jpg',
                'is_verified' => 0,
                'created_at' => '2026-01-24 18:40:11',
                'updated_at' => '2026-01-24 18:40:11',
            ],
            [
                'id' => 6,
                'name' => 'Leila S. Chudori',
                'username' => 'leilachudori',
                'email' => 'leila.chudori@mail.com',
                'password' => '$2y$10$dummyhashleila1234567890',
                'role' => 'USER',
                'bio' => 'Penulis novel dan jurnalis Indonesia, dikenal melalui karya sastra bertema sejarah dan kemanusiaan.',
                'profile_photo' => 'uploads/profiles/leila.jpg',
                'is_verified' => 1,
                'created_at' => '2026-01-27 16:45:53',
                'updated_at' => '2026-01-27 16:45:53',
            ],
            [
                'id' => 7,
                'name' => 'Pidi Baiq',
                'username' => 'pidibaiq',
                'email' => 'pidi.baiq@mail.com',
                'password' => '$2y$10$dummpyhashpidi1234567890',
                'role' => 'USER',
                'bio' => 'Penulis dan seniman Indonesia, dikenal lewat novel Dilan dan Milea.',
                'profile_photo' => 'uploads/profiles/pidi.jpg',
                'is_verified' => 1,
                'created_at' => '2026-01-27 16:45:53',
                'updated_at' => '2026-01-27 16:45:53',
            ],
            [
                'id' => 8,
                'name' => 'Shey Caelan',
                'username' => 'sheycaelan',
                'email' => 'shey.caelan@mail.com',
                'password' => '$2y$10$dummyhashshey1234567890',
                'role' => 'USER',
                'bio' => 'Penulis novel romance dengan tema emosi dan penyembuhan diri.',
                'profile_photo' => 'uploads/profiles/shey.jpg',
                'is_verified' => 1,
                'created_at' => '2026-01-27 16:45:53',
                'updated_at' => '2026-01-27 16:45:53',
            ],
            [
                'id' => 9,
                'name' => 'Brian Khrisna',
                'username' => 'briankhrisna',
                'email' => 'brian.khrisna@mail.com',
                'password' => '$2y$10$dummyhashbrian1234567890',
                'role' => 'USER',
                'bio' => 'Penulis novel reflektif tentang kehidupan dan makna kebahagiaan.',
                'profile_photo' => 'uploads/profiles/brian.jpg',
                'is_verified' => 1,
                'created_at' => '2026-01-27 16:45:53',
                'updated_at' => '2026-01-27 16:45:53',
            ],
            [
                'id' => 10,
                'name' => 'Tere Liye',
                'username' => 'tereliye',
                'email' => 'tere.liye@mail.com',
                'password' => '$2y$10$dummyhashtereliye1234567890',
                'role' => 'USER',
                'bio' => 'Penulis novel Indonesia yang dikenal melalui karya-karya bertema keluarga, kehidupan, perjuangan, dan nilai-nilai kemanusiaan.',
                'profile_photo' => 'uploads/profiles/tere-liye.jpg',
                'is_verified' => 1,
                'created_at' => '2026-01-27 16:46:22',
                'updated_at' => '2026-01-27 16:46:22',
            ],
            [
                'id' => 11,
                'name' => 'Khanza Haura',
                'username' => null,
                'email' => 'khanza.haura.148@gmail.com',
                'password' => '$2y$10$pSvZeaKUNjd6Z147b5f4B.kkgUKVaxu6uv9jPpbRBApE2GTXj1Wbe',
                'role' => 'USER',
                'bio' => null,
                'profile_photo' => null,
                'is_verified' => 0,
                'created_at' => '2026-02-01 15:18:37',
                'updated_at' => '2026-02-01 15:18:37',
            ],
        ];

        // Generate 10 random users dengan Faker
        $startId = 12;
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'id' => $startId + $i,
                'name' => $faker->name(),
                'username' => $faker->unique()->userName(),
                'email' => $faker->unique()->email(),
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'role' => $faker->randomElement(['USER', 'USER', 'USER']), // mostly USER
                'bio' => $faker->randomElement($bioSamples),
                'profile_photo' => null,
                'is_verified' => $faker->boolean(70), // 70% chance verified
                'created_at' => $faker->dateTimeBetween('-2 months')->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTimeBetween('-1 month')->format('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('users')->insertBatch($data);
    }
}
