<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyStoriesColumnsOrder extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        // Move genres column right after author_id
        $db->query('ALTER TABLE stories MODIFY genres VARCHAR(50) AFTER author_id');
    }

    public function down()
    {
        $db = \Config\Database::connect();
        // Move genres back to after description
        $db->query('ALTER TABLE stories MODIFY genres VARCHAR(50) AFTER description');
    }
}
