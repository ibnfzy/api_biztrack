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
   * Get stock information
   */
  public function index()
  {
    $barangId = $this->request->getGet('barangId');
    $builder  = $this->db->table('stok');

    if (!empty($barangId)) {
      $builder->where('barangId', $barangId);
    }

    $stocks = $builder->get()->getResult();

    return respondSuccess($this->response, $stocks);
  }

  /**
   * PUT /stok
   * Update stock
   */
  public function update()
  {
    $data = $this->request->getJSON(true);

    $barangId = $data['barangId'] ?? null;
    $quantity = (int) ($data['quantity'] ?? 0);
    $type     = $data['type'] ?? 'add';

    if (empty($barangId)) {
      return respondBadRequest($this->response, 'barangId is required');
    }

    $builder = $this->db->table('stok');
    $stock   = $builder->where('barangId', $barangId)->get()->getRowArray();

    $now = (new DateTime())->format('Y-m-d H:i:s');

    if ($stock) {
      $currentQuantity = (int) ($stock['quantity'] ?? 0);

      if ($type === 'subtract') {
        $newQuantity = max(0, $currentQuantity - $quantity);
      } else {
        $newQuantity = $currentQuantity + $quantity;
      }

      $builder->where('barangId', $barangId)->update([
        'quantity'    => $newQuantity,
        'lastUpdated' => $now,
      ]);
    } else {
      $newQuantity = $type === 'subtract' ? 0 : max(0, $quantity);

      $builder->insert([
        'barangId'    => $barangId,
        'quantity'    => $newQuantity,
        'lastUpdated' => $now,
      ]);
    }

    $updatedStock = $builder->where('barangId', $barangId)->get()->getRow();

    return respondSuccess($this->response, $updatedStock);
  }
}
