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
      'kode'       => $data['kode'] ?? null,
      'nama'       => $data['nama'] ?? null,
      'kategori'   => $data['kategori'] ?? null,
      'satuan'     => $data['satuan'] ?? null,
      'harga'      => $data['harga'] ?? 0,
      'supplier'   => $data['supplier'] ?? null,
      'stokMinimal'=> $data['stokMinimal'] ?? 0,
      'deskripsi'  => $data['deskripsi'] ?? null,
      'createdAt'  => $now,
      'updatedAt'  => $now,
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
    if (array_key_exists('kode', $data)) $update['kode'] = $data['kode'];
    if (array_key_exists('nama', $data)) $update['nama'] = $data['nama'];
    if (array_key_exists('kategori', $data)) $update['kategori'] = $data['kategori'];
    if (array_key_exists('satuan', $data)) $update['satuan'] = $data['satuan'];
    if (array_key_exists('harga', $data)) $update['harga'] = $data['harga'];
    if (array_key_exists('supplier', $data)) $update['supplier'] = $data['supplier'];
    if (array_key_exists('stokMinimal', $data)) $update['stokMinimal'] = $data['stokMinimal'];
    if (array_key_exists('deskripsi', $data)) $update['deskripsi'] = $data['deskripsi'];

    $update['updatedAt'] = (new DateTime())->format('Y-m-d H:i:s');

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
