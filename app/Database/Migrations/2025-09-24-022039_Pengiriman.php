<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pengiriman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11,  'auto_increment' => true],
            'permintaan_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nomor_pengiriman' => ['type' => 'VARCHAR', 'constraint' => 100],
            'tanggal_kirim'    => ['type' => 'DATETIME'],
            'penerima'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'alamat_tujuan'    => ['type' => 'TEXT'],
            'kurir'            => ['type' => 'VARCHAR', 'constraint' => 100],
            'status'           => ['type' => 'ENUM', 'constraint' => ['preparing', 'in_transit', 'completed', 'cancelled'], 'default' => 'preparing'],
            'catatan'          => ['type' => 'TEXT', 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('permintaan_id', 'permintaan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pengiriman');
    }

    public function down()
    {
        $this->forge->dropTable('pengiriman');
    }
}