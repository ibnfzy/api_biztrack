<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;
use DateTime;

class PermintaanController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * GET /permintaan
     * Retrieve all requests with their items.
     */
    public function index()
    {
        $requests = $this->db->table('permintaan')->get()->getResultArray();

        foreach ($requests as &$request) {
            $items = $this->db->table('permintaan_items')
                ->where('permintaanId', $request['id'])
                ->get()
                ->getResultArray();

            $request['items'] = $items;
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $requests,
        ]);
    }

    /**
     * POST /permintaan
     * Create a new request and related items.
     */
    public function create()
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        $insert = [
            'nomorPermintaan' => $data['nomorPermintaan'] ?? null,
            'tanggal'         => $data['tanggal'] ?? null,
            'peminta'         => $data['peminta'] ?? null,
            'prioritas'       => $data['prioritas'] ?? null,
            'status'          => $data['status'] ?? 'pending',
            'catatan'         => $data['catatan'] ?? null,
            'createdAt'       => $now,
            'updatedAt'       => $now,
        ];

        $this->db->table('permintaan')->insert($insert);
        $permintaanId = $this->db->insertID();

        $items = $data['items'] ?? [];
        foreach ($items as $item) {
            $itemInsert = [
                'permintaanId' => $permintaanId,
                'barangId'     => $item['barangId'] ?? null,
                'quantity'     => $item['quantity'] ?? 0,
                'satuan'       => $item['satuan'] ?? null,
                'keterangan'   => $item['keterangan'] ?? null,
                'createdAt'    => $now,
                'updatedAt'    => $now,
            ];

            $this->db->table('permintaan_items')->insert($itemInsert);
        }

        $created = $this->db->table('permintaan')
            ->where('id', $permintaanId)
            ->get()
            ->getRowArray();

        $created['items'] = $this->db->table('permintaan_items')
            ->where('permintaanId', $permintaanId)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data'    => $created,
        ]);
    }

    /**
     * PUT /permintaan/{id}
     * Update request details and optionally replace items.
     */
    public function update($id)
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        $update = [];
        $fields = ['nomorPermintaan', 'tanggal', 'peminta', 'prioritas', 'status', 'catatan'];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $update[$field] = $data[$field];
            }
        }

        if (! empty($update)) {
            $update['updatedAt'] = $now;
            $this->db->table('permintaan')->where('id', $id)->update($update);
        }

        if (array_key_exists('items', $data) && is_array($data['items'])) {
            $this->db->table('permintaan_items')->where('permintaanId', $id)->delete();

            foreach ($data['items'] as $item) {
                $itemInsert = [
                    'permintaanId' => $id,
                    'barangId'     => $item['barangId'] ?? null,
                    'quantity'     => $item['quantity'] ?? 0,
                    'satuan'       => $item['satuan'] ?? null,
                    'keterangan'   => $item['keterangan'] ?? null,
                    'createdAt'    => $now,
                    'updatedAt'    => $now,
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
                ->where('permintaanId', $id)
                ->get()
                ->getResultArray();
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $request,
        ]);
    }
}
