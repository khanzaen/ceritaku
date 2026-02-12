<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateViews extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'null' => true,
            ],
            'story_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'null' => true,
            ],
            'chapter_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('story_id');
        $this->forge->addKey('chapter_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('story_id', 'stories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('chapter_id', 'chapters', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('views');
    }

    public function down()
    {
        $this->forge->dropTable('views');
    }
}
