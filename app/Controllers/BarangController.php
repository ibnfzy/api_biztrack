<?php

namespace App\Controllers;

use Config\Database;
use DateTime;

class BarangController extends BaseController
{
  protected $db;

  public function __construct()
  {
    $this->db = Database::connect();
  }

  /**
   * GET /barang
   * Get all items
   */
  public function index()
  {
    $items = $this->db->table('barang')->get()->getResult();

    return respondSuccess($this->response, $items);
  }

  /**
   * POST /barang
   * Create item
   */
  public function create()
  {
        $data = $this->request->getJSON(true);
        $now = (new DateTime())->format('Y-m-d H:i:s');

        $insert = [
            'kode'           => $data['kode'] ?? '',
            'nama'           => $data['nama'] ?? '',
            'kategori'       => $data['kategori'] ?? '',
            'satuan'         => $data['satuan'] ?? '',
            'harga'          => $data['harga'] ?? 0,
            'supplier_id'    => $data['supplier_id'] ?? 0,
            'stok_minimal'   => $data['stok_minimal'] ?? 0,
            'deskripsi'      => $data['deskripsi'] ?? '',
            'created_at'     => $now,
            'updated_at'     => $now,
        ];

        $this->db->table('barang')->insert($insert);
        $id = $this->db->insertID();

        $item = $this->db->table('barang')->where('id', $id)->get()->getRow();

        return respondSuccess($this->response, $item, null, 201);
   }

  /**
   * PUT /barang/{id}
   * Update item
   */
  public function update($id)
  {
        $data = $this->request->getJSON(true);
        $update = [];

        if (array_key_exists('kode', $data))         $update['kode'] = $data['kode'] ?? '';
        if (array_key_exists('nama', $data))         $update['nama'] = $data['nama'] ?? '';
        if (array_key_exists('kategori', $data))     $update['kategori'] = $data['kategori'] ?? '';
        if (array_key_exists('satuan', $data))       $update['satuan'] = $data['satuan'] ?? '';
        if (array_key_exists('harga', $data))        $update['harga'] = $data['harga'] ?? 0;
        if (array_key_exists('supplier_id', $data))  $update['supplier_id'] = $data['supplier_id'] ?? 0;
        if (array_key_exists('stok_minimal', $data)) $update['stok_minimal'] = $data['stok_minimal'] ?? 0;
        if (array_key_exists('deskripsi', $data))    $update['deskripsi'] = $data['deskripsi'] ?? '';

        $update['updated_at'] = (new DateTime())->format('Y-m-d H:i:s');

        $this->db->table('barang')->where('id', $id)->update($update);

        $item = $this->db->table('barang')->where('id', $id)->get()->getRow();

        return respondSuccess($this->response, $item);
   }


  /**
   * DELETE /barang/{id}
   * Delete item
   */
  public function delete($id)
  {
    $this->db->table('barang')->where('id', $id)->delete();

    return respondSuccess($this->response, null, 'Item deleted successfully');
  }
}
