<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Barang extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11,  'auto_increment' => true],
            'kode'        => ['type' => 'VARCHAR', 'constraint' => 50],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 150],
            'kategori'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'satuan'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'harga'       => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'supplier'    => ['type' => 'VARCHAR', 'constraint' => 150],
            'stok_minimal' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'deskripsi'   => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('barang');
    }

    public function down()
    {
        $this->forge->dropTable('barang');
    }
}