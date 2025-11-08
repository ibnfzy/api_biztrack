<?php

namespace App\Controllers;

use Config\Database;
use DateTime;

class StockController extends BaseController
{
  protected $db;

  public function __construct()
  {
    $this->db = Database::connect();
  }

  /**
   * GET /stok
   * Get stock information (filtered per cabang)
   */
  public function index()
  {
    $barang_id = $this->request->getGet('barang_id');
    $cabang_id = $this->request->getGet('cabang_id');

    $builder = $this->db->table('stok');

    if (!empty($barang_id)) {
      $builder->where('barang_id', $barang_id);
    }

    if (!empty($cabang_id)) {
      $builder->where('cabang_id', $cabang_id);
    }

    $stocks = $builder->get()->getResult();

    return respondSuccess($this->response, $stocks);
  }

  /**
   * PUT /stok
   * Update or insert stock per cabang
   */
  public function update()
  {
    $data = $this->request->getJSON(true);

    $barang_id = $data['barang_id'] ?? null;
    $cabang_id = $data['cabang_id'] ?? null;
    $quantity  = (int) ($data['quantity'] ?? 0);
    $type      = $data['type'] ?? 'add';

    if (empty($barang_id)) {
      return respondBadRequest($this->response, 'barang_id is required');
    }

    if (empty($cabang_id)) {
      return respondBadRequest($this->response, 'cabang_id is required');
    }

    $builder = $this->db->table('stok');
    $stock   = $builder
      ->where('barang_id', $barang_id)
      ->where('cabang_id', $cabang_id)
      ->get()
      ->getRowArray();

    $now = (new DateTime())->format('Y-m-d H:i:s');

    // jika stok sudah ada → update
    if ($stock) {
      $currentQuantity = (int) ($stock['quantity'] ?? 0);

      if ($type === 'subtract') {
        $newQuantity = max(0, $currentQuantity - $quantity);
      } else {
        $newQuantity = $currentQuantity + $quantity;
      }

      $builder
        ->where('barang_id', $barang_id)
        ->where('cabang_id', $cabang_id)
        ->update([
          'quantity'         => $newQuantity,
          'terakhir_update'  => $now,
          'barang_nama'      => $data['barang_nama'] ?? $stock['barang_nama'],
          'supplier_id'      => $data['supplier_id'] ?? $stock['supplier_id'],
          'catatan'          => $data['catatan'] ?? null,
          'tanggal_masuk'    => $data['tanggal_masuk'] ?? date('Y-m-d'),
        ]);
    } 
    // jika stok belum ada → insert baru
    else {
      $newQuantity = $type === 'subtract' ? 0 : max(0, $quantity);

      $builder->insert([
        'barang_id'        => $barang_id,
        'cabang_id'        => $cabang_id,
        'barang_nama'      => $data['barang_nama'] ?? '-',
        'supplier_id'      => $data['supplier_id'] ?? 0,
        'quantity'         => $newQuantity,
        'terakhir_update'  => $now,
        'created_at'       => $now,
        'catatan'          => $data['catatan'] ?? null,
        'tanggal_masuk'    => $data['tanggal_masuk'] ?? date('Y-m-d'),
      ]);
    }

    $updatedStock = $builder
      ->where('barang_id', $barang_id)
      ->where('cabang_id', $cabang_id)
      ->get()
      ->getRow();

    return respondSuccess($this->response, $updatedStock, 'Stok berhasil diperbarui.');
  }
}
