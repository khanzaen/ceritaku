<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Romance',
                'slug' => 'romance',
                'description' => 'Cerita tentang cinta, hubungan, dan perjalanan emosional dua hati.',
                'color' => '#ec4899',
                'icon' => 'favorite',
            ],
            [
                'name' => 'Fantasy',
                'slug' => 'fantasy',
                'description' => 'Petualangan di dunia magis penuh makhluk legendaris dan keajaiban.',
                'color' => '#a855f7',
                'icon' => 'auto_awesome',
            ],
            [
                'name' => 'Mystery',
                'slug' => 'mystery',
                'description' => 'Pencarian kebenaran di balik misteri dan kerahasiaan yang tersembunyi.',
                'color' => '#06b6d4',
                'icon' => 'manage_search',
            ],
            [
                'name' => 'Thriller',
                'slug' => 'thriller',
                'description' => 'Cerita penuh ketegangan yang membuat adrenalin naik setiap halaman.',
                'color' => '#dc2626',
                'icon' => 'sentiment_very_satisfied',
            ],
            [
                'name' => 'Science Fiction',
                'slug' => 'science-fiction',
                'description' => 'Eksplorasi masa depan, teknologi canggih, dan dimensi baru realitas.',
                'color' => '#0891b2',
                'icon' => 'rocket_launch',
            ],
            [
                'name' => 'Drama',
                'slug' => 'drama',
                'description' => 'Cerita mendalam tentang kehidupan, konflik internal, dan pertumbuhan personal.',
                'color' => '#f97316',
                'icon' => 'theater_comedy',
            ],
            [
                'name' => 'Horror',
                'slug' => 'horror',
                'description' => 'Cerita menakutkan yang membuat merinding dan jantung berdetak cepat.',
                'color' => '#1f2937',
                'icon' => 'sentiment_dissatisfied',
            ],
            [
                'name' => 'Adventure',
                'slug' => 'adventure',
                'description' => 'Petualangan seru menembus berbagai rintangan dan tantangan berbahaya.',
                'color' => '#16a34a',
                'icon' => 'hiking',
            ],
            [
                'name' => 'Comedy',
                'slug' => 'comedy',
                'description' => 'Cerita menghibur penuh tawa, humor, dan situasi kocak yang lucu.',
                'color' => '#fbbf24',
                'icon' => 'sentiment_very_satisfied',
            ],
            [
                'name' => 'Historical',
                'slug' => 'historical',
                'description' => 'Cerita berlatar belakang sejarah dengan tokoh dan peristiwa nyata.',
                'color' => '#92400e',
                'icon' => 'history',
            ],
            [
                'name' => 'Action',
                'slug' => 'action',
                'description' => 'Aksi seru penuh pertarungan, pertempuran, dan momen mendebarkan.',
                'color' => '#7c3aed',
                'icon' => 'sports_martial_arts',
            ],
            [
                'name' => 'Slice of Life',
                'slug' => 'slice-of-life',
                'description' => 'Momen-momen sederhana kehidupan sehari-hari yang penuh makna.',
                'color' => '#84cc16',
                'icon' => 'pets',
            ],
        ];

        $this->db->table('genres')->insertBatch($data);
    }
}
