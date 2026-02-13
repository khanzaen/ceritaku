<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StoryGenreSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Get all stories dengan genre data
        $stories = $db->table('stories')
            ->select('id, genres')
            ->where('genres IS NOT NULL', null, false)
            ->where('genres !=', '')
            ->get()
            ->getResultArray();
        
        // Get all genres untuk mapping
        $genres = $db->table('genres')
            ->select('id, name')
            ->get()
            ->getResultArray();
        
        // Create name -> id mapping
        $genreMap = [];
        foreach ($genres as $genre) {
            $genreMap[strtolower(trim($genre['name']))] = $genre['id'];
        }
        
        // Process setiap story
        $storyGenreData = [];
        foreach ($stories as $story) {
            // Parse comma-separated genres
            $genreNames = array_map('trim', explode(',', $story['genres']));
            
            foreach ($genreNames as $genreName) {
                $genreNameLower = strtolower($genreName);
                
                // Cari genre_id dari mapping
                if (isset($genreMap[$genreNameLower])) {
                    $storyGenreData[] = [
                        'story_id' => $story['id'],
                        'genre_id' => $genreMap[$genreNameLower],
                    ];
                }
            }
        }
        
        // Insert ke story_genres
        if (!empty($storyGenreData)) {
            $this->db->table('story_genres')->insertBatch($storyGenreData);
            echo "✓ Berhasil migrate " . count($storyGenreData) . " genre mappings\n";
        } else {
            echo "! Tidak ada data genre untuk dimigrasikan\n";
        }
    }
}
