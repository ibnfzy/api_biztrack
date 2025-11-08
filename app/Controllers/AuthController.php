<?php

namespace App\Controllers;

use Config\Database;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\Clock\SystemClock;
use DateTimeImmutable;

class AuthController extends BaseController
{
  private $config;

  public function __construct()
  {
    // Konfigurasi JWT
    $this->config = Configuration::forSymmetricSigner(
      new \Lcobucci\JWT\Signer\Hmac\Sha256(),
      \Lcobucci\JWT\Signer\Key\InMemory::plainText('biztrack')
    );
  }

  public function options()
{
    return $this->response->setStatusCode(200);
}

  public function login()
  {
    $db = Database::connect();
    $email = $this->request->getVar('email');
    $password = $this->request->getVar('password');

    $builder = $db->table('users u')
        ->select('u.*, c.nama_cabang, c.alamat, c.telepon')
        ->join('cabang c', 'c.id = u.cabang_id', 'left')
        ->where('u.email', $email);

    $user = $builder->get()->getRow();

    if (!$user || !password_verify($password, $user->password)) {
      return respondUnauthorized($this->response, 'Invalid credentials');
    }

    $now   = new DateTimeImmutable();
    $token = $this->config->builder()
      ->identifiedBy(bin2hex(random_bytes(8))) // jti
      ->issuedAt($now)                     // iat
      ->canOnlyBeUsedAfter($now)           // nbf
      ->expiresAt($now->modify('+1 hour')) // exp
      ->withClaim('uid', $user->id)        // custom claim
      ->withClaim('role', $user->role)
      ->getToken($this->config->signer(), $this->config->signingKey());

    return respondSuccess($this->response, [
        'id'          => $user->id,
        'email'       => $user->email,
        'name'        => $user->name,
        'role'        => $user->role,
        'cabang_id'   => $user->cabang_id,
        'nama_cabang' => $user->nama_cabang,
        'alamat_cabang' => $user->alamat,
        'telepon_cabang' => $user->telepon,
        'token'       => $token->toString(),
    ]);
  }

  public function logout()
  {
    return respondSuccess($this->response, null, 'Logout successful. Please remove token from client storage.');
  }
}
