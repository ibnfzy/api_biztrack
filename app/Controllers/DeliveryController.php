<?php

namespace App\Controllers;

use Config\Database;
use DateTime;

class DeliveryController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * GET /pengiriman
     * Retrieve all deliveries with their items, optionally filtered by cabang_id
     */
    public function index()
    {
        $cabang_id = $this->request->getGet('cabang_id');

        $builder = $this->db->table('pengiriman');

        if (!empty($cabang_id)) {
            $builder->where('tujuan_cabang_id', $cabang_id);
        }

        $deliveries = $builder->orderBy('id', 'DESC')->get()->getResultArray();

        foreach ($deliveries as &$delivery) {
            $items = $this->db->table('pengiriman_items')
                ->where('pengiriman_id', $delivery['id'])
                ->get()
                ->getResultArray();

            $delivery['items'] = $items;
        }

        return respondSuccess($this->response, $deliveries);
    }

    /**
     * POST /pengiriman
     * Create a new delivery with its items (per cabang)
     */
    public function create()
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        $cabang_id = $data['cabang_id'] ?? null;

        if (empty($cabang_id)) {
            return respondBadRequest($this->response, 'cabang_id is required.');
        }

        // Generate nomor_pengiriman otomatis
        $last = $this->db->table('pengiriman')
            ->select('id')
            ->orderBy('id', 'DESC')
            ->get()
            ->getRow();

        $nextId = $last ? $last->id + 1 : 1;
        $nomorPengiriman = 'DLV-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        // Insert ke tabel pengiriman
        $insert = [
            'tujuan_cabang_id' => $data['tujuan_cabang_id'],
            'asal_cabang_id' => $cabang_id,
            'permintaan_id'    => $data['permintaan_id'] ?? null,
            'nomor_pengiriman' => $nomorPengiriman,
            'tanggal_kirim'    => $data['tanggal_kirim'] ?? date('Y-m-d'),
            'penerima'         => $data['penerima'] ?? 'Leader Cabang',
            'alamat_tujuan'    => $data['alamat_tujuan'] ?? '',
            'kurir'            => $data['kurir'] ?? 'Staff Gudang',
            'status'           => $data['status'] ?? 'preparing',
            'catatan'          => $data['catatan'] ?? null,
            'created_at'       => $now,
            'updated_at'       => $now,
        ];

        $this->db->table('pengiriman')->insert($insert);
        $deliveryId = $this->db->insertID();

        // Insert items jika ada
        $items = $data['items'] ?? [];
        foreach ($items as $item) {
            $itemInsert = [
                'pengiriman_id' => $deliveryId,
                'barang_id'     => $item['barang_id'] ?? null,
                'quantity'      => $item['quantity'] ?? 0,
                'satuan'        => $item['satuan'] ?? '',
                'kondisi'       => $item['kondisi'] ?? 'baik',
            ];
            $this->db->table('pengiriman_items')->insert($itemInsert);
        }

        // Ambil data pengiriman baru
        $created = $this->db->table('pengiriman')
            ->where('id', $deliveryId)
            ->get()
            ->getRowArray();

        if ($created) {
            $created['items'] = $this->db->table('pengiriman_items')
                ->where('pengiriman_id', $deliveryId)
                ->get()
                ->getResultArray();
        }

        return respondSuccess($this->response, $created, 'Pengiriman berhasil dibuat.', 201);
    }

    /**
     * PUT /pengiriman/{id}
     * Update delivery details (per cabang) and optionally replace items.
     */
    public function update($id)
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        $update = [];
        $fields = [
            'permintaan_id',
            'nomor_pengiriman',
            'tanggal_kirim',
            'penerima',
            'alamat_tujuan',
            'kurir',
            'status',
            'catatan',
            'cabang_id',
        ];

        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $update[$field] = $data[$field];
            }
        }

        if (!empty($update)) {
            $update['updated_at'] = $now;
            $this->db->table('pengiriman')->where('id', $id)->update($update);
        }

        if (array_key_exists('items', $data) && is_array($data['items'])) {
            $this->db->table('pengiriman_items')->where('pengiriman_id', $id)->delete();

            foreach ($data['items'] as $item) {
                $itemInsert = [
                    'pengiriman_id' => $id,
                    'barang_id'     => $item['barang_id'] ?? null,
                    'quantity'      => $item['quantity'] ?? 0,
                    'satuan'        => $item['satuan'] ?? '',
                    'kondisi'       => $item['kondisi'] ?? 'baik',
                ];
                $this->db->table('pengiriman_items')->insert($itemInsert);
            }
        }

        $delivery = $this->db->table('pengiriman')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if ($delivery) {
            $delivery['items'] = $this->db->table('pengiriman_items')
                ->where('pengiriman_id', $id)
                ->get()
                ->getResultArray();
        }

        return respondSuccess($this->response, $delivery, 'Data pengiriman berhasil diperbarui.');
    }
}
