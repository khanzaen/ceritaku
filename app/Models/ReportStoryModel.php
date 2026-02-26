<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportStoryModel extends Model
{
    protected $table = 'report_story';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'story_id',
        'user_id',
        'report_reason',
        'description',
        'evidence_image',
        'status',
        'admin_note',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation (optional)
    protected $validationRules = [
        'story_id'      => 'required|integer',
        'report_reason' => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}