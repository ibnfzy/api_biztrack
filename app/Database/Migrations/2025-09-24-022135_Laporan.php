<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Laporan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11,  'auto_increment' => true],
            'judul'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'jenis'            => ['type' => 'VARCHAR', 'constraint' => 50], // contoh: konfirmasi, pengiriman, stok, bulanan, pengadaan, maintenance, audit, emergency
            'status'           => ['type' => 'VARCHAR', 'constraint' => 50],
            'konten'           => ['type' => 'TEXT', 'null' => true],
            'items'            => ['type' => 'JSON', 'null' => true], // list barang
            'dibuat_oleh'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'tanggal_dibuat'   => ['type' => 'DATETIME'],
            'periode_dari'     => ['type' => 'DATETIME', 'null' => true],
            'periode_sampai'   => ['type' => 'DATETIME', 'null' => true],
            'budget_diajukan'  => ['type' => 'BIGINT', 'null' => true],
            'vendor_recommended' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'items_terdampak'  => ['type' => 'JSON', 'null' => true],
            'estimated_completion' => ['type' => 'DATETIME', 'null' => true],
            'auditor_eksternal' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'accuracy_score'   => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'discrepancies_found' => ['type' => 'INT', 'null' => true],
            'priority'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'escalated_to'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'sla_deadline'     => ['type' => 'DATETIME', 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('laporan');
    }

    public function down()
    {
        $this->forge->dropTable('laporan');
    }
}