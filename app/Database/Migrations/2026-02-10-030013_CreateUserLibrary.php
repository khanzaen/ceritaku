<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserLibrary extends Migration
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
            ],
            'story_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
            ],
            'progress' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Chapter terakhir yang dibaca (0 = belum mulai)',
            ],
            'is_reading' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => '1 = sedang dibaca, 0 = sudah selesai',
            ],
            'added_at' => [
                'type' => 'TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('story_id', 'stories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_library');
    }

    public function down()
    {
        $this->forge->dropTable('user_library');
    }
}
