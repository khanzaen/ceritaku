<?php
require_once 'vendor/autoload.php';

$pathsConfig = new \Config\Paths();
$bootstrap = \CodeIgniter\Boot::bootWeb($pathsConfig);
$app = $bootstrap->getApp();

$db = \Config\Database::connect();

echo "🔍 Checking database: db_ceritaku2\n\n";

// Check stories
$stories = $db->query("SELECT COUNT(*) as total FROM stories WHERE status='PUBLISHED'")->getRow();
echo "📚 Stories (PUBLISHED): {$stories->total}\n";

// Check reviews
$reviews = $db->query("SELECT COUNT(*) as total FROM reviews")->getRow();
echo "📝 Reviews: {$reviews->total}\n";

// Check review_likes
$likes = $db->query("SELECT COUNT(*) as total FROM review_likes")->getRow();
echo "❤️  Review Likes: {$likes->total}\n";

// Check users
$users = $db->query("SELECT COUNT(*) as total FROM users")->getRow();
echo "👥 Users: {$users->total}\n";

// Test featured query
echo "\n🎯 Testing Featured Reviews Query:\n";
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

echo "   Found: " . count($featured) . " featured reviews\n";

if (count($featured) === 0) {
    echo "\n❌ No data found! Please run seeders:\n";
    echo "   php spark db:seed UserSeeder\n";
    echo "   php spark db:seed StorySeeder\n";
    echo "   php spark db:seed ReviewSeeder\n";
    echo "   php spark db:seed ReviewLikeSeeder\n";
} else {
    echo "\n✅ Data is ready! Featured section should work now.\n";
    echo "\nSample review:\n";
    echo "   - Story: {$featured[0]->story_title}\n";
    echo "   - User: {$featured[0]->user_name}\n";
    echo "   - Review: " . substr($featured[0]->review, 0, 60) . "...\n";
}
