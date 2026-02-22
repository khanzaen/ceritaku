<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropUserPostsAndCommentsFinal extends Migration
{
    public function up()
    {
        // Drop user_post_comments if exists
        if ($this->db->tableExists('user_post_comments')) {
            $this->forge->dropTable('user_post_comments', true);
        }
        // Drop user_posts if exists
        if ($this->db->tableExists('user_posts')) {
            $this->forge->dropTable('user_posts', true);
        }
    }

    public function down()
    {
        // No rollback
    }
}
