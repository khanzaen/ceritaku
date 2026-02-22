<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropUserPostAndUserLikesTables extends Migration
{
    public function up()
    {
        // Drop user_post_comments if it exists
        if ($this->db->tableExists('user_post_comments')) {
            $this->forge->dropTable('user_post_comments', true);
        }
        // Drop user_posts table
        if ($this->db->tableExists('user_posts')) {
            $this->forge->dropTable('user_posts', true);
        }
    }

    public function down()
    {
        // No rollback (optional: you can recreate the tables here if needed)
    }
}
