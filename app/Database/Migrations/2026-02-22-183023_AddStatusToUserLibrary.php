<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToUserLibrary extends Migration
{
    public function up()
    {
        $this->forge->addColumn('user_library', [
            'status' => [
                'type' => "ENUM",
                'constraint' => ['reading', 'finished'],
                'default' => 'reading',
                'after' => 'is_reading',
                'comment' => 'Status baca: reading, finished'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('user_library', 'status');
    }
}
