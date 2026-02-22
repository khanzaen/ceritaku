<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsFeaturedToStories extends Migration
{
    public function up()
    {
        $fields = [
            'is_featured' => [
                'type'       => 'BOOLEAN',
                'default'    => 0,
                'null'       => false,
                'after'      => 'publication_status', 
            ],
        ];
        $this->forge->addColumn('stories', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('stories', 'is_featured');
    }
}
