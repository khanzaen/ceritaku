<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProviderToUsers extends Migration
{
    public function up()
    {
        // Tambahkan kolom hanya jika belum ada
        if (!$this->db->fieldExists('provider', 'users')) {
            $this->forge->addColumn('users', [
                'provider' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'email',
                ],
                'provider_id' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'provider',
                ],
            ]);
        }

        // Password nullable karena user OAuth tidak punya password
        $this->forge->modifyColumn('users', [
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['provider', 'provider_id']);

        $this->forge->modifyColumn('users', [
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
        ]);
    }
}