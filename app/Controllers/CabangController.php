<?php

namespace App\Controllers;

use Config\Database;
use DateTime;

class CabangController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // 📍 GET /api/cabang
    public function index()
    {
        $data = $this->db->table('cabang')->orderBy('id', 'ASC')->get()->getResultArray();

        return respondSuccess($this->response, $data, 'Data cabang berhasil diambil.');
    }

    // 📍 GET /api/cabang/{id}
    public function show($id)
    {
        $cabang = $this->db->table('cabang')->where('id', $id)->get()->getRowArray();

        if (!$cabang) {
            return respondNotFound($this->response, "Cabang dengan ID {$id} tidak ditemukan.");
        }

        return respondSuccess($this->response, $cabang, 'Detail cabang berhasil diambil.');
    }

    // 📍 POST /api/cabang
    public function create()
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        $fields = ['nama_cabang', 'alamat', 'telepon'];
        $insert = [];

        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $insert[$field] = $data[$field];
            }
        }

        if (empty($insert['nama_cabang'])) {
            return respondBadRequest($this->response, "Field 'nama_cabang' wajib diisi.");
        }

        $insert['created_at'] = $now;
        $insert['updated_at'] = $now;

        try {
            $this->db->table('cabang')->insert($insert);
            $insert['id'] = $this->db->insertID();

            return respondSuccess($this->response, $insert, 'Cabang berhasil ditambahkan.', 201);
        } catch (\Throwable $e) {
            return respondServerError($this->response, 'Gagal menambahkan cabang: ' . $e->getMessage());
        }
    }

    // 📍 PUT /api/cabang/{id}
    public function update($id)
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        $update = [];
        $fields = ['nama_cabang', 'alamat', 'telepon'];

        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $update[$field] = $data[$field];
            }
        }

        if (empty($update)) {
            return respondBadRequest($this->response, 'Tidak ada data yang dikirim untuk diperbarui.');
        }

        try {
            $update['updated_at'] = $now;
            $affected = $this->db->table('cabang')->where('id', $id)->update($update);

            if (!$affected) {
                return respondNotFound($this->response, "Cabang dengan ID {$id} tidak ditemukan.");
            }

            $cabang = $this->db->table('cabang')->where('id', $id)->get()->getRowArray();

            return respondSuccess($this->response, $cabang, 'Cabang berhasil diperbarui.');
        } catch (\Throwable $e) {
            return respondServerError($this->response, 'Gagal memperbarui cabang: ' . $e->getMessage());
        }
    }

    // 📍 DELETE /api/cabang/{id}
    public function delete($id)
    {
        $exists = $this->db->table('cabang')->where('id', $id)->get()->getRowArray();

        if (!$exists) {
            return respondNotFound($this->response, "Cabang dengan ID {$id} tidak ditemukan.");
        }

        try {
            $this->db->table('cabang')->where('id', $id)->delete();

            return respondSuccess($this->response, null, "Cabang dengan ID {$id} berhasil dihapus.");
        } catch (\Throwable $e) {
            return respondServerError($this->response, 'Gagal menghapus cabang: ' . $e->getMessage());
        }
    }
}
