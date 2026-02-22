<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'bio',
        'profile_photo',
        'provider',
        'provider_id',
        'is_verified'
    ];

    public function __construct()
    {
        parent::__construct();
        // Force array return type
        $this->returnType = 'array';
    }

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name'     => 'required|min_length[3]|max_length[100]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'username' => 'permit_empty|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'password' => 'permit_empty|min_length[8]',
        'role'     => 'in_list[USER,ADMIN]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = false;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    /**
     * Get user by email
     */
    public function getUserByEmail(string $email)
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)->where('email', $email)->get()->getRowArray();
    }

    /**
     * Get user by username
     */
    public function getUserByUsername(string $username)
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)->where('username', $username)->get()->getRowArray();
    }

    /**
     * Get total users count
     */
    public function getTotalUsers(): int
    {
        return $this->countAll();
    }

    /**
     * Get authors (users who have published stories)
     */
    public function getAuthors()
    {
        return $this->select('users.*')
            ->join('stories', 'stories.author_id = users.id')
            ->where('stories.status', 'PUBLISHED')
            ->groupBy('users.id')
            ->findAll();
    }

    /**
     * Get popular authors (sorted by number of stories and total views)
     */
    public function getPopularAuthors(int $limit = 6)
    {
        $db = \Config\Database::connect();
        
        return $db->table('users')
            ->select('users.id, users.name, users.profile_photo, users.bio, 
                COUNT(DISTINCT stories.id) as total_stories,
                COUNT(DISTINCT user_library.id) as total_readers,
                COUNT(DISTINCT reviews.id) as total_reviews')
            ->join('stories', 'stories.author_id = users.id', 'left')
            ->where('stories.status', 'PUBLISHED')
            ->join('user_library', 'user_library.story_id = stories.id', 'left')
            ->join('reviews', 'reviews.story_id = stories.id', 'left')
            ->groupBy('users.id')
            ->orderBy('total_stories', 'DESC')
            ->orderBy('total_reviews', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Verify user
     */
    public function verifyUser(int $userId): bool
    {
        return $this->update($userId, ['is_verified' => 1]);
    }

    /**
     * Authenticate user with email and password
     * Returns user array if credentials are valid, null otherwise
     */
    public function authenticate(string $email, string $password): ?array
    {
        // Use DB builder directly to bypass returnType issues
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $user = $builder->where('email', $email)->get()->getRowArray();
        
        if (!$user) {
            return null;
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            return null;
        }
        
        return $user;
    }
    /**
 * Cari user berdasarkan provider + provider_id
 */
public function getUserByProvider(string $provider, string $providerId): ?array
{
    $db = \Config\Database::connect();
    return $db->table($this->table)
        ->where('provider', $provider)
        ->where('provider_id', $providerId)
        ->get()
        ->getRowArray() ?: null;
}

/**
 * Cari atau buat user dari OAuth provider.
 * Alur:
 * 1. Ada provider_id → langsung login
 * 2. Ada email tapi belum link → link provider ke akun lama
 * 3. Belum ada sama sekali → register otomatis tanpa password
 */
public function findOrCreateFromProvider(array $data): array|false
{
    $db = \Config\Database::connect();

    // 1. Sudah pernah login dengan provider ini
    $user = $this->getUserByProvider($data['provider'], $data['provider_id']);
    if ($user) return $user;

    // 2. Email sudah terdaftar → link provider
    $existing = $db->table($this->table)->where('email', $data['email'])->get()->getRowArray();
    if ($existing) {
        $db->table($this->table)->where('id', $existing['id'])->update([
            'provider'    => $data['provider'],
            'provider_id' => $data['provider_id'],
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        return $db->table($this->table)->where('id', $existing['id'])->get()->getRowArray();
    }

    // 3. User baru — register otomatis
    $inserted = $db->table($this->table)->insert([
        'name'          => $data['name'],
        'email'         => $data['email'],
        'password'      => null,
        'provider'      => $data['provider'],
        'provider_id'   => $data['provider_id'],
        'profile_photo' => $data['profile_photo'] ?? null,
        'role'          => 'USER',
        'is_verified'   => 1, // email Google sudah verified
        'created_at'    => date('Y-m-d H:i:s'),
        'updated_at'    => date('Y-m-d H:i:s'),
    ]);

    if (!$inserted) return false;

    return $db->table($this->table)->where('id', $db->insertID())->get()->getRowArray();
}
    /**
     * Create new user with validation
     * Returns user array if successful, false with errors otherwise
     */
    public function createUser(array $data): array|false
    {
        // Use DB builder directly to bypass returnType issues
        $db = \Config\Database::connect();
        
        // Check if email already exists - create new builder instance
        $existing = $db->table($this->table)->where('email', $data['email'])->get()->getRowArray();
        if ($existing) {
            return false;
        }
        
        // Prepare user data with hashed password
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role' => $data['role'] ?? 'USER',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        // Insert user - create new builder instance
        $insertResult = $db->table($this->table)->insert($userData);
        
        if (!$insertResult) {
            return false;
        }
        
        // Get the inserted user ID
        $userId = $db->insertID();
        
        // Fetch and return the created user as array - create new builder instance
        $newUser = $db->table($this->table)->where('id', $userId)->get()->getRowArray();
        
        // Final safeguard: ensure it's really an array
        if (!is_array($newUser)) {
            return false;
        }
        
        return $newUser;
    }
}
