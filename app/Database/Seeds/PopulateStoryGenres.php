<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PopulateStoryGenres extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Genre assignment per story dengan judul sebenarnya
        $storyGenres = [
            1 => ['Drama', 'Historical Fiction', 'Mystery'],                    // Laut Bercerita
            2 => ['Romance', 'Teen'],                                  // Dilan 1990
            3 => ['Romance', 'Drama', 'Mystery'],                               // Love Me When It Hurts
            4 => ['Drama', 'Slice of Life'],                                    // Seporsi Mie Ayam Sebelum Mati
            5 => ['Romance', 'Drama'],                                          // Milea
            6 => ['Science Fiction', 'Romance', 'Mystery'],                     // Hujan
            7 => ['Religious', 'Drama'],                                        // Rindu
            8 => ['Romance', 'Drama'],                                          // Daun yang Jatuh Tak Pernah Membenci Angin
            9 => ['Fantasy', 'Adventure', 'Mystery'],                           // Bumi
            10 => ['Fantasy', 'Adventure', 'Mystery'],                          // Bulan
            11 => ['Fantasy', 'Adventure'],                                     // Bintang
            12 => ['Fantasy', 'Adventure'],                                     // Matahari
        ];
        
        // Update setiap story dengan genre-nya
        foreach ($storyGenres as $storyId => $genres) {
            $genreString = implode(', ', $genres);
            $db->table('stories')
                ->where('id', $storyId)
                ->update(['genres' => $genreString]);
        }
        
        echo "✓ Berhasil update " . count($storyGenres) . " stories dengan genres yang tepat\n";
    }
}
