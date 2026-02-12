<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckData extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:check';
    protected $description = 'Check database tables and data';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        CLI::write('🔍 Checking database: db_ceritaku2', 'yellow');
        CLI::newLine();

        // Check stories
        $stories = $db->query("SELECT COUNT(*) as total FROM stories WHERE status='PUBLISHED'")->getRow();
        CLI::write("📚 Stories (PUBLISHED): {$stories->total}");

        // Check reviews
        $reviews = $db->query("SELECT COUNT(*) as total FROM reviews")->getRow();
        CLI::write("📝 Reviews: {$reviews->total}");

        // Check review_likes
        $likes = $db->query("SELECT COUNT(*) as total FROM review_likes")->getRow();
        CLI::write("❤️  Review Likes: {$likes->total}");

        // Check users
        $users = $db->query("SELECT COUNT(*) as total FROM users")->getRow();
        CLI::write("👥 Users: {$users->total}");

        CLI::newLine();
        CLI::write('🎯 Testing Featured Reviews Query:', 'yellow');
        
        // Test featured query
        $featured = $db->query("
            SELECT 
                reviews.*,
                stories.title as story_title,
                users.name as user_name
            FROM reviews
            INNER JOIN stories ON stories.id = reviews.story_id
            INNER JOIN users ON users.id = reviews.user_id
            WHERE stories.status = 'PUBLISHED'
            LIMIT 6
        ")->getResult();

        CLI::write("   Found: " . count($featured) . " featured reviews");

        if (count($featured) === 0) {
            CLI::newLine();
            CLI::error('❌ No data found! Please run seeders:');
            CLI::write('   php spark db:seed UserSeeder');
            CLI::write('   php spark db:seed StorySeeder');
            CLI::write('   php spark db:seed ReviewSeeder');
            CLI::write('   php spark db:seed ReviewLikeSeeder');
        } else {
            CLI::newLine();
            CLI::write('✅ Data is ready! Featured section should work now.', 'green');
            CLI::newLine();
            CLI::write('Sample review:');
            CLI::write("   - Story: {$featured[0]->story_title}");
            CLI::write("   - User: {$featured[0]->user_name}");
            CLI::write("   - Review: " . substr($featured[0]->review, 0, 60) . "...");
        }
    }
}
