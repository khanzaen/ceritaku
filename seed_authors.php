<?php
/**
 * Simple Author Seeder
 * Run: php seed_authors.php
 */

// Database connection
$db = new mysqli(
    'localhost',
    'root',
    '',
    'db_ceritaku'
);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$authors = [
    [
        'name' => 'Dewi Lestari',
        'username' => 'dewi_lestari',
        'email' => 'dewi@ceritaku.test',
        'bio' => 'Penulis fiksi dan penyair Indonesia yang terkenal.',
        'profile_photo' => 'assets/authors/dewi.jpg'
    ],
    [
        'name' => 'Pidi Baiq',
        'username' => 'pidi_baiq',
        'email' => 'pidi@ceritaku.test',
        'bio' => 'Penulis novel Dilan yang fenomenal di Indonesia.',
        'profile_photo' => 'assets/authors/pidi.jpg'
    ],
    [
        'name' => 'Leila S. Chudori',
        'username' => 'leila_chudori',
        'email' => 'leila@ceritaku.test',
        'bio' => 'Novelis dan jurnalis pemenang berbagai penghargaan.',
        'profile_photo' => 'assets/authors/leila.jpg'
    ],
    [
        'name' => 'Eka Kurniawan',
        'username' => 'eka_kurniawan',
        'email' => 'eka@ceritaku.test',
        'bio' => 'Penulis sastra kontemporer dengan karya internasional.',
        'profile_photo' => 'assets/authors/eka.jpg'
    ],
    [
        'name' => 'Fira Basuki',
        'username' => 'fira_basuki',
        'email' => 'fira@ceritaku.test',
        'bio' => 'Penulis romance dan lifestyle yang disukai banyak pembaca.',
        'profile_photo' => 'assets/authors/fira.jpg'
    ],
    [
        'name' => 'Tere Liye',
        'username' => 'tere_liye',
        'email' => 'tere@ceritaku.test',
        'bio' => 'Penulis bestseller dengan jutaan pembaca setia.',
        'profile_photo' => 'assets/authors/tere.jpg'
    ]
];

$password = password_hash('password123', PASSWORD_BCRYPT);
$now = date('Y-m-d H:i:s');

foreach ($authors as $author) {
    $name = $db->real_escape_string($author['name']);
    $username = $db->real_escape_string($author['username']);
    $email = $db->real_escape_string($author['email']);
    $bio = $db->real_escape_string($author['bio']);
    $profile_photo = $db->real_escape_string($author['profile_photo']);
    
    $sql = "INSERT INTO users (name, username, email, password, bio, profile_photo, role, is_verified, created_at, updated_at) 
            VALUES ('$name', '$username', '$email', '$password', '$bio', '$profile_photo', 'USER', 1, '$now', '$now')";
    
    if ($db->query($sql) === TRUE) {
        echo "✓ Author '$name' berhasil ditambahkan\n";
    } else {
        echo "✗ Error: " . $db->error . "\n";
    }
}

$db->close();
echo "\nSeeding selesai!\n";
?>
