<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Company extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'    => ['type' => 'VARCHAR', 'constraint' => 150],
            'address' => ['type' => 'TEXT', 'null' => true],
            'phone'   => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'email'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'logo'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'settings' => ['type' => 'JSON', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('company');
    }

    public function down()
    {
        $this->forge->dropTable('company');
    }
}