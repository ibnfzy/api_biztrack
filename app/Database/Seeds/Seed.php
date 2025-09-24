<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Seed extends Seeder
{
    public function run()
    {
        $this->call('Users');
        $this->call('Suppliers');
        $this->call('Barang');

        $this->call('Permintaan');
        $this->call('Pengiriman');
        $this->call('Laporan');
        $this->call('Stok');
    }
}