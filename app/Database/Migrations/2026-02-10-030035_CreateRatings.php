<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRatings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'auto_increment' => true,
            ],
            'story_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
            ],
            'rating' => [
                'type' => 'INT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['story_id', 'user_id']);
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('story_id', 'stories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ratings');
    }

    public function down()
    {
        $this->forge->dropTable('ratings');
    }
}
