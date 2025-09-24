<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Pengiriman extends Seeder
{
    public function run()
    {
        $pengirimanData = [
            [
                'permintaan_id'    => 2,
                'nomor_pengiriman' => 'SHIP-001',
                'tanggal_kirim'    => date('Y-m-d H:i:s', strtotime('-6 hours')),
                'penerima'         => 'Admin Pusat',
                'alamat_tujuan'    => 'Lantai 2, Gedung A',
                'kurir'            => 'Staff Gudang',
                'status'           => 'completed',
                'catatan'          => 'Pengiriman berhasil, barang diterima dengan baik',
                'created_at'       => date('Y-m-d H:i:s', strtotime('-6 hours')),
            ],
            [
                'permintaan_id'    => 3,
                'nomor_pengiriman' => 'SHIP-002',
                'tanggal_kirim'    => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'penerima'         => 'Divisi Marketing',
                'alamat_tujuan'    => 'Lantai 3, Gedung B',
                'kurir'            => 'Staff Gudang',
                'status'           => 'in_transit',
                'catatan'          => 'Sedang dalam perjalanan ke tujuan',
                'created_at'       => date('Y-m-d H:i:s', strtotime('-2 hours')),
            ],
        ];

        $this->db->table('pengiriman')->insertBatch($pengirimanData);

        // Insert detail items ke tabel pengiriman_items
        $itemsData = [
            [
                'pengiriman_id' => 1,
                'barang_id'     => 3,
                'quantity'      => 20,
                'satuan'        => 'rim',
                'kondisi'       => 'baik',
            ],
            [
                'pengiriman_id' => 2,
                'barang_id'     => 2,
                'quantity'      => 10,
                'satuan'        => 'unit',
                'kondisi'       => 'baik',
            ],
            [
                'pengiriman_id' => 2,
                'barang_id'     => 4,
                'quantity'      => 5,
                'satuan'        => 'botol',
                'kondisi'       => 'baik',
            ],
        ];

        $this->db->table('pengiriman_items')->insertBatch($itemsData);
    }
}