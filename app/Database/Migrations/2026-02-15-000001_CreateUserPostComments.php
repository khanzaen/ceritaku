<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserPostComments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'auto_increment' => true,
            ],
            'user_post_id' => [
                'type' => 'BIGINT',
            ],
            'user_id' => [
                'type' => 'BIGINT',
            ],
            'comment' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_post_id', 'user_posts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_post_comments');
    }

    public function down()
    {
        $this->forge->dropTable('user_post_comments');
    }
}
