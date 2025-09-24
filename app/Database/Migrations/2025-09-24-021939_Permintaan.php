<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Permintaan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11,  'auto_increment' => true],
            'nomor_permintaan'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'tanggal'           => ['type' => 'DATETIME'],
            'peminta'           => ['type' => 'VARCHAR', 'constraint' => 150],
            'prioritas'         => ['type' => 'ENUM', 'constraint' => ['low', 'medium', 'high']],
            'status'            => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected', 'confirmed', 'shipped'], 'default' => 'pending'],
            'catatan'           => ['type' => 'TEXT', 'null' => true],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('permintaan');
    }

    public function down()
    {
        $this->forge->dropTable('permintaan');
    }
}