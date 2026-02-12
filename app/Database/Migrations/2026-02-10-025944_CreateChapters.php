<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChapters extends Migration
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
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'chapter_number' => [
                'type' => 'INT',
            ],
            'content' => [
                'type' => 'LONGTEXT',
            ],
            'is_premium' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['DRAFT', 'PUBLISHED', 'ARCHIVED'],
                'default' => 'DRAFT',
            ],
            'view_count' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['story_id', 'chapter_number']);
        $this->forge->addForeignKey('story_id', 'stories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('chapters');
    }

    public function down()
    {
        $this->forge->dropTable('chapters');
    }
}
