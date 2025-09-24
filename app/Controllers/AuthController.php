<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\Clock\SystemClock;
use DateTimeImmutable;

class AuthController extends Controller
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

  public function login()
  {
    $db = Database::connect();
    $email = $this->request->getVar('email');
    $password = $this->request->getVar('password');

    $user = $db->table('users')->where('email', $email)->get()->getRow();

    if (!$user || !password_verify($password, $user->password)) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Invalid credentials'
      ]);
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

    return $this->response->setJSON([
      'success' => true,
      'data' => [
        'user' => [
          'id' => $user->id,
          'email' => $user->email,
          'role' => $user->role,
          'token' => $token->toString()
        ]
      ]
    ]);
  }

  public function logout()
  {
    return $this->response->setJSON([
      'success' => true,
      'message' => 'Logout successful. Please remove token from client storage.'
    ]);
  }
}