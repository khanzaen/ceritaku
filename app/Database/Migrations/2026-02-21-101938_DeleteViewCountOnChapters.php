<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DeleteViewCountOnChapters extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('chapters', 'view_count');
    }

    public function down()
    {
        $fields = [
            'view_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'content', // letakkan setelah kolom content
            ],
        ];
        $this->forge->addColumn('chapters', $fields);
    }
}
