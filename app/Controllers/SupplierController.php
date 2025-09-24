<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;
use DateTime;

class SupplierController extends Controller
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
    $suppliers = $this->db->table('supplier')->get()->getResult();

    return $this->response->setJSON([
      'success' => true,
      'data'    => $suppliers,
    ]);
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
      'createdAt' => $now,
      'updatedAt' => $now,
    ];

    $this->db->table('supplier')->insert($insert);
    $id = $this->db->insertID();

    $supplier = $this->db->table('supplier')->where('id', $id)->get()->getRow();

    return $this->response->setJSON([
      'success' => true,
      'data'    => $supplier,
    ]);
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

    $update['updatedAt'] = (new DateTime())->format('Y-m-d H:i:s');

    $this->db->table('supplier')->where('id', $id)->update($update);

    $supplier = $this->db->table('supplier')->where('id', $id)->get()->getRow();

    return $this->response->setJSON([
      'success' => true,
      'data'    => $supplier,
    ]);
  }

  /**
   * DELETE /supplier/{id}
   * Delete supplier
   */
  public function delete($id)
  {
    $this->db->table('supplier')->where('id', $id)->delete();

    return $this->response->setJSON([
      'success' => true,
      'message' => 'Supplier deleted successfully',
    ]);
  }
}
