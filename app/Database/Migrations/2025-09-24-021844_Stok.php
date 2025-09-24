<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Stok extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11,  'auto_increment' => true],
            'barang_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'barang_nama'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'jumlah'          => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'minimum_stok'    => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'lokasi'          => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'terakhir_update' => ['type' => 'DATETIME'],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('barang_id', 'barang', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('stok');
    }

    public function down()
    {
        $this->forge->dropTable('stok');
    }
}