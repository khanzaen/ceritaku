<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateComments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'auto_increment' => true,
            ],
            'chapter_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
            ],
            'comment' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('chapter_id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('chapter_id', 'chapters', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('comments');
    }

    public function down()
    {
        $this->forge->dropTable('comments');
    }
}
