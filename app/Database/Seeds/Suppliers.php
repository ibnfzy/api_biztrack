<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Suppliers extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'       => 'PT Teknologi Maju',
                'alamat'     => 'Jl. Sudirman No. 123, Jakarta',
                'telepon'    => '021-1234567',
                'email'      => 'contact@teknologimaju.com',
                'kontak'     => 'Budi Santoso',
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'CV Sumber Rejeki',
                'alamat'     => 'Jl. Gatot Subroto No. 45, Bandung',
                'telepon'    => '022-9876543',
                'email'      => 'info@sumberrejeki.co.id',
                'kontak'     => 'Siti Nurhaliza',
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('suppliers')->insertBatch($data);
    }
}