<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropRatingsTable extends Migration
{
    public function up()
    {
        $this->forge->dropTable('ratings', true);
    }

    public function down()
    {
        // Optional: recreate table if needed
    }
}
