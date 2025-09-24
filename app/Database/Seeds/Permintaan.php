<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Permintaan extends Seeder
{
    public function run()
    {
        $permintaanData = [
            [
                'nomor_permintaan' => 'REQ-001',
                'tanggal'          => date('Y-m-d H:i:s', strtotime('-2 days')),
                'peminta'          => 'Staff Gudang',
                'prioritas'        => 'medium',
                'status'           => 'pending',
                'catatan'          => 'Untuk tim development baru',
                'created_at'       => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'nomor_permintaan' => 'REQ-002',
                'tanggal'          => date('Y-m-d H:i:s', strtotime('-1 days')),
                'peminta'          => 'Staff Gudang',
                'prioritas'        => 'medium',
                'status'           => 'confirmed',
                'catatan'          => 'Stok habis untuk kebutuhan printing',
                'created_at'       => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],
            [
                'nomor_permintaan' => 'REQ-003',
                'tanggal'          => date('Y-m-d H:i:s', strtotime('-3 days')),
                'peminta'          => 'Staff Gudang',
                'prioritas'        => 'medium',
                'status'           => 'confirmed',
                'catatan'          => 'Permintaan dari divisi marketing',
                'created_at'       => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
        ];

        $this->db->table('permintaan')->insertBatch($permintaanData);

        // Insert detail items ke tabel permintaan_items
        $itemsData = [
            [
                'permintaan_id' => 1,
                'barang_id'     => 1,
                'quantity'      => 5,
                'satuan'        => 'unit',
                'keterangan'    => 'Laptop Dell XPS 13',
            ],
            [
                'permintaan_id' => 1,
                'barang_id'     => 2,
                'quantity'      => 5,
                'satuan'        => 'unit',
                'keterangan'    => 'Mouse Wireless Logitech',
            ],
            [
                'permintaan_id' => 2,
                'barang_id'     => 3,
                'quantity'      => 20,
                'satuan'        => 'rim',
                'keterangan'    => 'Kertas A4 80gsm',
            ],
            [
                'permintaan_id' => 3,
                'barang_id'     => 2,
                'quantity'      => 10,
                'satuan'        => 'unit',
                'keterangan'    => 'Mouse Wireless Logitech',
            ],
            [
                'permintaan_id' => 3,
                'barang_id'     => 4,
                'quantity'      => 5,
                'satuan'        => 'botol',
                'keterangan'    => 'Tinta Printer Canon',
            ],
        ];

        $this->db->table('permintaan_items')->insertBatch($itemsData);
    }
}