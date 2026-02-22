<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRatingToReviews extends Migration
{
    public function up()
    {
        $this->forge->addColumn('reviews', [
            'rating' => [
                'type' => 'INT',
                'constraint' => 2,
                'null' => true,
                'after' => 'review', // letakkan setelah kolom review
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('reviews', 'rating');
    }
}
