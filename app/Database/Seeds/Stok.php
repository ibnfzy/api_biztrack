<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Stok extends Seeder
{
    public function run()
    {
        $stokData = [
            [
                'barang_id'       => 1,
                'barang_nama'     => 'Laptop Dell XPS 13',
                'jumlah'          => 15,
                'minimum_stok'    => 5,
                'lokasi'          => 'Gudang A - Rak 1',
                'terakhir_update' => date('Y-m-d H:i:s'),
                'created_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'barang_id'       => 2,
                'barang_nama'     => 'Mouse Wireless Logitech',
                'jumlah'          => 45,
                'minimum_stok'    => 10,
                'lokasi'          => 'Gudang A - Rak 2',
                'terakhir_update' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'created_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'barang_id'       => 3,
                'barang_nama'     => 'Kertas A4 80gsm',
                'jumlah'          => 80,
                'minimum_stok'    => 20,
                'lokasi'          => 'Gudang B - Rak 1',
                'terakhir_update' => date('Y-m-d H:i:s', strtotime('-6 hours')),
                'created_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'barang_id'       => 4,
                'barang_nama'     => 'Tinta Printer Canon',
                'jumlah'          => 3,
                'minimum_stok'    => 5,
                'lokasi'          => 'Gudang A - Rak 3',
                'terakhir_update' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'created_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'barang_id'       => 5,
                'barang_nama'     => 'Headset Gaming HyperX',
                'jumlah'          => 12,
                'minimum_stok'    => 3,
                'lokasi'          => 'Gudang A - Rak 4',
                'terakhir_update' => date('Y-m-d H:i:s'),
                'created_at'      => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('stok')->insertBatch($stokData);
    }
}