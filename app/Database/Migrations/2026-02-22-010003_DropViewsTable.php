<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropViewsTable extends Migration
{
    public function up()
    {
        $this->forge->dropTable('views', true);
    }

    public function down()
    {
        // Optional: recreate table if needed
    }
}
