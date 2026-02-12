<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStories extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'author_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
            ],
            'genres' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'cover_image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['DRAFT', 'PENDING_REVIEW', 'PUBLISHED', 'ARCHIVED'],
                'default' => 'DRAFT',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('author_id');
        $this->forge->addForeignKey('author_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('stories');
    }

    public function down()
    {
        $this->forge->dropTable('stories');
    }
}
