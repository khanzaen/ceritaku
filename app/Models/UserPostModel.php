<?php

namespace App\Models;

use CodeIgniter\Model;

class UserPostModel extends Model
{
    protected $table = 'user_posts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'content', 'created_at', 'updated_at', 'is_featured'];
    protected $returnType = 'array';
    public $timestamps = false;
}
