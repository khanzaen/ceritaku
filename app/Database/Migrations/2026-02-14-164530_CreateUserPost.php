<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserPost extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'BIGINT',
            ],
            'content' => [
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
            'is_featured' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null'    => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_posts');
    }

    public function down()
    {
        $this->forge->dropTable('user_posts');
    }
}
