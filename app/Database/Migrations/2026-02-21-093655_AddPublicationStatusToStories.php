<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublicationStatusToStories extends Migration
{
    public function up()
    {
        $fields = [
            'publication_status' => [
                'type' => 'ENUM',
                'constraint' => ['Ongoing', 'Completed', 'On Hiatus'],
                'default' => 'Ongoing',
                'after' => 'status', // letakkan setelah kolom status
            ],
        ];
        $this->forge->addColumn('stories', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('stories', 'publication_status');
    }
}
