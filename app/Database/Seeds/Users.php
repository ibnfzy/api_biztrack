<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Users extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'       => 'Leader Center',
                'email'      => 'leader@demo.com',
                'password'   => password_hash('demo123', PASSWORD_DEFAULT),
                'role'       => 'leader',
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Manajer Center',
                'email'      => 'manager@demo.com',
                'password'   => password_hash('demo123', PASSWORD_DEFAULT),
                'role'       => 'manager',
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Staff Gudang',
                'email'      => 'staff@demo.com',
                'password'   => password_hash('demo123', PASSWORD_DEFAULT),
                'role'       => 'staff',
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Owner',
                'email'      => 'owner@demo.com',
                'password'   => password_hash('demo123', PASSWORD_DEFAULT),
                'role'       => 'owner',
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}