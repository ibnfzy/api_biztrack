<?php

namespace App\Controllers;

use Config\Database;
use DateTime;

class UserController extends BaseController
{
  protected $db;

  public function __construct()
  {
    $this->db = Database::connect();
  }

  /**
   * GET /users
   * Get all users
   */
  public function index()
  {
    $users = $this->db->table('users')->get()->getResult();

    return respondSuccess($this->response, $users);
  }

  /**
   * POST /users
   * Create user
   */
  public function create()
  {
    $data = $this->request->getJSON(true);

    $insert = [
      'name'      => $data['name'] ?? null,
      'email'     => $data['email'] ?? null,
      'password'  => password_hash($data['password'], PASSWORD_BCRYPT),
      'role'      => $data['role'] ?? 'staff',
      'status'    => 'active',
      'createdAt' => (new DateTime())->format('Y-m-d H:i:s'),
    ];

    $this->db->table('users')->insert($insert);
    $id = $this->db->insertID();

    return respondSuccess($this->response, [
      'id'        => $id,
      'name'      => $insert['name'],
      'email'     => $insert['email'],
      'role'      => $insert['role'],
      'status'    => $insert['status'],
      'createdAt' => $insert['createdAt']
    ], null, 201);
  }

  /**
   * PUT /users/{id}
   * Update user
   */
  public function update($id)
  {
    $data = $this->request->getJSON(true);

    $update = [];
    if (!empty($data['name'])) $update['name'] = $data['name'];
    if (!empty($data['email'])) $update['email'] = $data['email'];
    if (!empty($data['role'])) $update['role'] = $data['role'];
    if (!empty($data['status'])) $update['status'] = $data['status'];

    $update['updatedAt'] = (new DateTime())->format('Y-m-d H:i:s');

    $this->db->table('users')->where('id', $id)->update($update);

    $user = $this->db->table('users')->where('id', $id)->get()->getRow();

    return respondSuccess($this->response, $user);
  }

  /**
   * DELETE /users/{id}
   * Delete user
   */
  public function delete($id)
  {
    $this->db->table('users')->where('id', $id)->delete();

    return respondSuccess($this->response, null, 'User deleted successfully');
  }
}