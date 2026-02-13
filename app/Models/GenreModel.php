<?php

namespace App\Models;

use CodeIgniter\Model;

class GenreModel extends Model
{
    protected $table            = 'genres';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'slug',
        'description',
        'color',
        'icon'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'name'        => 'required|string|max_length[100]|is_unique[genres.name]',
        'slug'        => 'required|string|max_length[120]|is_unique[genres.slug]',
        'description' => 'string|max_length[1000]',
        'color'       => 'string|max_length[7]',
        'icon'        => 'string|max_length[50]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setSlug'];
    protected $beforeUpdate   = ['setSlug'];

    /**
     * Auto-generate slug from name
     */
    protected function setSlug(array $data)
    {
        if (!isset($data['data']['slug']) || empty($data['data']['slug'])) {
            $data['data']['slug'] = url_title($data['data']['name'], '-', true);
        }

        return $data;
    }

    /**
     * Get all genres
     */
    public function getAllGenres()
    {
        return $this->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Get genre by slug
     */
    public function getBySlug(string $slug)
    {
        return $this->where('slug', $slug)->first();
    }

    /**
     * Get genre with story count
     */
    public function getGenresWithCount()
    {
        $db = \Config\Database::connect();
        $sql = "SELECT genres.*, COUNT(DISTINCT stories.id) as story_count
                FROM genres
                LEFT JOIN stories ON FIND_IN_SET(genres.id, CONCAT(stories.genres))
                WHERE stories.status = 'PUBLISHED'
                GROUP BY genres.id
                ORDER BY story_count DESC";
        return $db->query($sql)->getResultArray();
    }
}
