<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserPostLikes extends Migration
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
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_post_id', 'user_id']);
        $this->forge->addForeignKey('user_post_id', 'user_posts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_post_likes');
    }

    public function down()
    {
        $this->forge->dropTable('user_post_likes');
    }
}
