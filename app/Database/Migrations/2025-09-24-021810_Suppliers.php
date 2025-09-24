<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Suppliers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11,  'auto_increment' => true],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 150],
            'kontak'     => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'alamat'     => ['type' => 'TEXT', 'null' => true],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'telepon'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'status'     => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('suppliers');
    }

    public function down()
    {
        $this->forge->dropTable('suppliers');
    }
}