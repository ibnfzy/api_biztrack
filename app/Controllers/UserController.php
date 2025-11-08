<?php

namespace App\Controllers;

use Config\Database;
use DateTime;

class UserController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * GET /users
     * Retrieve all users (optionally filtered by cabang_id)
     */
    public function index()
    {
        $cabang_id = $this->request->getGet('cabang_id');

        $builder = $this->db->table('users');

        if (!empty($cabang_id)) {
            $builder->where('cabang_id', $cabang_id);
        }

        $users = $builder->orderBy('id', 'ASC')->get()->getResultArray();

        return respondSuccess($this->response, $users);
    }

    /**
     * POST /users
     * Create new user
     */
    public function create()
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        // Validasi field wajib
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return respondBadRequest($this->response, 'name, email, dan password wajib diisi.');
        }

        if (empty($data['cabang_id'])) {
            return respondBadRequest($this->response, 'cabang_id wajib diisi.');
        }

        // Cegah duplikat email
        $existing = $this->db->table('users')->where('email', $data['email'])->get()->getRow();
        if ($existing) {
            return respondBadRequest($this->response, 'Email sudah terdaftar.');
        }

        $insert = [
            'cabang_id'  => $data['cabang_id'],
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => password_hash($data['password'], PASSWORD_BCRYPT),
            'role'       => $data['role'] ?? 'staff',
            'status'     => $data['status'] ?? 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $this->db->table('users')->insert($insert);
        $id = $this->db->insertID();

        $user = $this->db->table('users')->where('id', $id)->get()->getRowArray();

        unset($user['password']); // Jangan tampilkan password hash

        return respondSuccess($this->response, $user, 'User berhasil dibuat.', 201);
    }

    /**
     * PUT /users/{id}
     * Update user data
     */
    public function update($id)
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        $update = [];

        $fields = ['name', 'email', 'role', 'status', 'cabang_id'];
        foreach ($fields as $field) {
            if (!empty($data[$field])) {
                $update[$field] = $data[$field];
            }
        }

        if (!empty($data['password'])) {
            $update['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        if (empty($update)) {
            return respondBadRequest($this->response, 'Tidak ada data yang diubah.');
        }

        $update['updated_at'] = $now;

        $this->db->table('users')->where('id', $id)->update($update);

        $user = $this->db->table('users')->where('id', $id)->get()->getRowArray();

        if ($user) {
            unset($user['password']);
        }

        return respondSuccess($this->response, $user, 'User berhasil diperbarui.');
    }

    /**
     * DELETE /users/{id}
     * Delete user
     */
    public function delete($id)
    {
        $deleted = $this->db->table('users')->where('id', $id)->delete();

        if (!$deleted) {
            return respondBadRequest($this->response, 'User tidak ditemukan.');
        }

        return respondSuccess($this->response, null, 'User berhasil dihapus.');
    }
}
