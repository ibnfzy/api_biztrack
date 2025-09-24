<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Barang extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode'       => 'BRG001',
                'nama'       => 'Laptop Dell XPS 13',
                'kategori'   => 'Elektronik',
                'satuan'     => 'unit',
                'harga'      => 15000000,
                'deskripsi'  => 'Laptop premium untuk kebutuhan bisnis',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'kode'       => 'BRG002',
                'nama'       => 'Mouse Wireless Logitech',
                'kategori'   => 'Aksesoris',
                'satuan'     => 'unit',
                'harga'      => 350000,
                'deskripsi'  => 'Mouse wireless dengan teknologi advanced optical tracking',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'kode'       => 'BRG003',
                'nama'       => 'Kertas A4 80gsm',
                'kategori'   => 'Alat Tulis',
                'satuan'     => 'rim',
                'harga'      => 45000,
                'deskripsi'  => 'Kertas fotokopi berkualitas tinggi untuk keperluan kantor',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('barang')->insertBatch($data);
    }
}