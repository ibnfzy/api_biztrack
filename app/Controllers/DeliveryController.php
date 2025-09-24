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
     * Retrieve all deliveries with their items.
     */
    public function index()
    {
        $deliveries = $this->db->table('pengiriman')->get()->getResultArray();

        foreach ($deliveries as &$delivery) {
            $items = $this->db->table('pengiriman_items')
                ->where('pengirimanId', $delivery['id'])
                ->get()
                ->getResultArray();

            $delivery['items'] = $items;
        }

        return respondSuccess($this->response, $deliveries);
    }

    /**
     * POST /pengiriman
     * Create a new delivery with its items.
     */
    public function create()
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        $insert = [
            'permintaanId'    => $data['permintaanId'] ?? null,
            'nomorPengiriman' => $data['nomorPengiriman'] ?? null,
            'tanggalKirim'    => $data['tanggalKirim'] ?? null,
            'penerima'        => $data['penerima'] ?? null,
            'alamatTujuan'    => $data['alamatTujuan'] ?? null,
            'kurir'           => $data['kurir'] ?? null,
            'status'          => $data['status'] ?? 'preparing',
            'catatan'         => $data['catatan'] ?? null,
            'createdAt'       => $now,
            'updatedAt'       => $now,
        ];

        $this->db->table('pengiriman')->insert($insert);
        $deliveryId = $this->db->insertID();

        $items = $data['items'] ?? [];
        foreach ($items as $item) {
            $itemInsert = [
                'pengirimanId' => $deliveryId,
                'barangId'     => $item['barangId'] ?? null,
                'quantity'     => $item['quantity'] ?? 0,
                'satuan'       => $item['satuan'] ?? null,
                'kondisi'      => $item['kondisi'] ?? null,
                'createdAt'    => $now,
                'updatedAt'    => $now,
            ];

            $this->db->table('pengiriman_items')->insert($itemInsert);
        }

        $created = $this->db->table('pengiriman')
            ->where('id', $deliveryId)
            ->get()
            ->getRowArray();

        if ($created) {
            $created['items'] = $this->db->table('pengiriman_items')
                ->where('pengirimanId', $deliveryId)
                ->get()
                ->getResultArray();
        }

        return respondSuccess($this->response, $created, null, 201);
    }

    /**
     * PUT /pengiriman/{id}
     * Update delivery details and optionally replace items.
     */
    public function update($id)
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        $update = [];
        $fields = [
            'permintaanId',
            'nomorPengiriman',
            'tanggalKirim',
            'penerima',
            'alamatTujuan',
            'kurir',
            'status',
            'catatan',
        ];

        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $update[$field] = $data[$field];
            }
        }

        if (! empty($update)) {
            $update['updatedAt'] = $now;
            $this->db->table('pengiriman')->where('id', $id)->update($update);
        }

        if (array_key_exists('items', $data) && is_array($data['items'])) {
            $this->db->table('pengiriman_items')->where('pengirimanId', $id)->delete();

            foreach ($data['items'] as $item) {
                $itemInsert = [
                    'pengirimanId' => $id,
                    'barangId'     => $item['barangId'] ?? null,
                    'quantity'     => $item['quantity'] ?? 0,
                    'satuan'       => $item['satuan'] ?? null,
                    'kondisi'      => $item['kondisi'] ?? null,
                    'createdAt'    => $now,
                    'updatedAt'    => $now,
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
                ->where('pengirimanId', $id)
                ->get()
                ->getResultArray();
        }

        return respondSuccess($this->response, $delivery);
    }
}
