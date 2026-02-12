<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviewLikes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'auto_increment' => true,
            ],
            'review_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['review_id', 'user_id']);
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('review_id', 'reviews', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('review_likes');
    }

    public function down()
    {
        $this->forge->dropTable('review_likes');
    }
}
