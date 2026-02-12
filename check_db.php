<?php
// Check and create database
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'db_ceritaku2';

try {
    // Connect to MySQL server (without database)
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Koneksi MySQL berhasil!\n\n";
    
    // Show all databases
    echo "📋 Daftar Database:\n";
    $result = $pdo->query("SHOW DATABASES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $marker = ($row[0] == $dbname) ? " 👈 (Database target)" : "";
        echo "   - {$row[0]}$marker\n";
    }
    
    // Check if our database exists
    $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
    $exists = $stmt->fetch();
    
    if ($exists) {
        echo "\n✅ Database '$dbname' sudah ada!\n";
        
        // Count tables in database
        $pdo->exec("USE $dbname");
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "   Jumlah tabel: " . count($tables) . "\n";
        if (count($tables) > 0) {
            echo "   Tabel: " . implode(", ", $tables) . "\n";
        }
    } else {
        echo "\n❌ Database '$dbname' TIDAK ADA!\n";
        echo "🔧 Membuat database...\n";
        
        // Create database
        $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        echo "✅ Database '$dbname' berhasil dibuat!\n";
        echo "\n🚀 Jalankan perintah ini:\n";
        echo "   php spark migrate\n";
        echo "   php spark db:seed UserSeeder\n";
        echo "   php spark db:seed StorySeeder\n";
        echo "   php spark db:seed ChapterSeeder\n";
        echo "   php spark db:seed ReviewSeeder\n";
        echo "   php spark db:seed RatingSeeder\n";
        echo "   php spark db:seed CommentSeeder\n";
        echo "   php spark db:seed ReviewLikeSeeder\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
