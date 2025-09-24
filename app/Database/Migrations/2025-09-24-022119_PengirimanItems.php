<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PengirimanItems extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'pengiriman_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'barang_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'quantity'     => ['type' => 'INT', 'constraint' => 11],
            'satuan'       => ['type' => 'VARCHAR', 'constraint' => 50],
            'kondisi'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pengiriman_id', 'pengiriman', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('barang_id', 'barang', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pengiriman_items');
    }

    public function down()
    {
        $this->forge->dropTable('pengiriman_items');
    }
}