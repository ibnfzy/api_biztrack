<?php

namespace App\Controllers;

use Config\Database;
use DateTime;

class PermintaanController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * GET /permintaan
     * Retrieve all requests with their items, optionally filtered by cabang_id
     */
    public function index()
    {
        $cabang_id = $this->request->getGet('cabang_id');

        $builder = $this->db->table('permintaan');
        if (!empty($cabang_id)) {
            $builder->where('cabang_id', $cabang_id);
        }

        $requests = $builder->orderBy('id', 'DESC')->get()->getResultArray();

        foreach ($requests as &$request) {
            $items = $this->db->table('permintaan_items')
                ->select('permintaan_items.*, barang.nama as barang_nama')
                ->where('permintaan_id', $request['id'])
                ->join('barang', 'permintaan_items.barang_id=barang.id')
                ->get()
                ->getResultArray();

            $request['items'] = $items;
        }

        return respondSuccess($this->response, $requests);
    }

    /**
     * POST /permintaan
     * Create a new request and related items (per cabang)
     */
    public function create()
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        $cabang_id = $data['cabang_id'] ?? null;
        $id_user   = $data['id_user'] ?? null;

        if (empty($cabang_id)) {
            return respondBadRequest($this->response, 'cabang_id is required.');
        }

        if (empty($id_user)) {
            return respondBadRequest($this->response, 'id_user is required.');
        }

        // generate nomor permintaan
        $last = $this->db->table('permintaan')->select('id')->orderBy('id', 'DESC')->get()->getRow();
        $nextId = $last ? $last->id + 1 : 1;
        $nomorPermintaan = 'PR-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        // insert ke tabel permintaan
        $insert = [
            'cabang_id'        => $cabang_id,
            'asal_cabang_id'   => $data['asal_cabang_id'],
            'tujuan_cabang_id' => $data['tujuan_cabang_id'],
            'id_user'          => $id_user,
            'nomor_permintaan' => $nomorPermintaan,
            'tanggal'          => $data['tanggal'] ?? $now,
            'peminta'          => $data['peminta'] ?? '',
            'prioritas'        => $data['prioritas'] ?? 'low',
            'status'           => $data['status'] ?? 'pending',
            'catatan'          => $data['catatan'] ?? null,
            'created_at'       => $now,
            'updated_at'       => $now,
        ];

        $this->db->table('permintaan')->insert($insert);
        $permintaan_id = $this->db->insertID();

        // insert item permintaan
        $items = $data['items'] ?? [];
        foreach ($items as $item) {
            $itemInsert = [
                'permintaan_id' => $permintaan_id,
                'barang_id'     => $item['barangId'] ?? null,
                'quantity'      => $item['quantity'] ?? 0,
                'satuan'        => $item['satuan'] ?? null,
                'keterangan'    => $item['keterangan'] ?? null,
            ];
            $this->db->table('permintaan_items')->insert($itemInsert);
        }

        // ambil data permintaan beserta items
        $created = $this->db->table('permintaan')
            ->where('id', $permintaan_id)
            ->get()
            ->getRowArray();

        $created['items'] = $this->db->table('permintaan_items')
            ->where('permintaan_id', $permintaan_id)
            ->get()
            ->getResultArray();

        return respondSuccess($this->response, $created, 'Permintaan berhasil dibuat.', 201);
    }

    /**
     * PUT /permintaan/{id}
     * Update request details (per cabang)
     */
    public function update($id)
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        $update = [];
        $fields = ['nomor_permintaan', 'tanggal', 'peminta', 'prioritas', 'status', 'catatan', 'cabang_id'];

        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $update[$field] = $data[$field];
            }
        }

        if (!empty($update)) {
            $update['updated_at'] = $now;
            $this->db->table('permintaan')->where('id', $id)->update($update);
        }

        if (array_key_exists('items', $data) && is_array($data['items'])) {
            $this->db->table('permintaan_items')->where('permintaan_id', $id)->delete();

            foreach ($data['items'] as $item) {
                $itemInsert = [
                    'permintaan_id' => $id,
                    'barang_id'     => $item['barangId'] ?? null,
                    'quantity'      => $item['quantity'] ?? 0,
                    'satuan'        => $item['satuan'] ?? null,
                    'keterangan'    => $item['keterangan'] ?? null,
                ];
                $this->db->table('permintaan_items')->insert($itemInsert);
            }
        }

        $request = $this->db->table('permintaan')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if ($request) {
            $request['items'] = $this->db->table('permintaan_items')
                ->where('permintaan_id', $id)
                ->get()
                ->getResultArray();
        }

        return respondSuccess($this->response, $request, 'Permintaan berhasil diperbarui.');
    }
}
