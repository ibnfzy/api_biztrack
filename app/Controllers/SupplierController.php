<?php

namespace App\Controllers;

use Config\Database;
use DateTime;

class SupplierController extends BaseController
{
  protected $db;

  public function __construct()
  {
    $this->db = Database::connect();
  }

  /**
   * GET /supplier
   * Get all suppliers
   */
  public function index()
  {
    $suppliers = $this->db->table('suppliers')->get()->getResult();

    return respondSuccess($this->response, $suppliers);
  }

  /**
   * POST /supplier
   * Create supplier
   */
  public function create()
  {
    $data = $this->request->getJSON(true);

    $now = (new DateTime())->format('Y-m-d H:i:s');

    $insert = [
      'nama'      => $data['nama'] ?? null,
      'kontak'    => $data['kontak'] ?? null,
      'alamat'    => $data['alamat'] ?? null,
      'email'     => $data['email'] ?? null,
      'telepon'   => $data['telepon'] ?? null,
      'status'    => 'active',
      'created_at' => $now,
      'updated_at' => $now,
    ];

    $this->db->table('suppliers')->insert($insert);
    $id = $this->db->insertID();

    $supplier = $this->db->table('suppliers')->where('id', $id)->get()->getRow();

    return respondSuccess($this->response, $supplier, null, 201);
  }

  /**
   * PUT /supplier/{id}
   * Update supplier
   */
  public function update($id)
  {
    $data = $this->request->getJSON(true);

    $update = [];
    if (array_key_exists('nama', $data)) $update['nama'] = $data['nama'];
    if (array_key_exists('kontak', $data)) $update['kontak'] = $data['kontak'];
    if (array_key_exists('alamat', $data)) $update['alamat'] = $data['alamat'];
    if (array_key_exists('email', $data)) $update['email'] = $data['email'];
    if (array_key_exists('telepon', $data)) $update['telepon'] = $data['telepon'];
    if (array_key_exists('status', $data)) $update['status'] = $data['status'];

    $update['updated_at'] = (new DateTime())->format('Y-m-d H:i:s');

    $this->db->table('suppliers')->where('id', $id)->update($update);

    $supplier = $this->db->table('suppliers')->where('id', $id)->get()->getRow();

    return respondSuccess($this->response, $supplier);
  }

  /**
   * DELETE /supplier/{id}
   * Delete supplier
   */
  public function delete($id)
  {
    $this->db->table('suppliers')->where('id', $id)->delete();

    return respondSuccess($this->response, null, 'Supplier deleted successfully');
  }
}
