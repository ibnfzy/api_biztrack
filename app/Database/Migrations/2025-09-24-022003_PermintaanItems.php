<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PermintaanItems extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'permintaan_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'barang_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'quantity'    => ['type' => 'INT', 'constraint' => 11],
            'satuan'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'keterangan'  => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('permintaan_id', 'permintaan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('barang_id', 'barang', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('permintaan_items');
    }

    public function down()
    {
        $this->forge->dropTable('permintaan_items');
    }
}