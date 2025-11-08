<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\Clock\SystemClock;
use DateTimeZone;
use Config\Services;

class JwtFilter implements FilterInterface
{
    private $config;

    public function __construct()
    {
        $this->config = Configuration::forSymmetricSigner(
            new \Lcobucci\JWT\Signer\Hmac\Sha256(),
            \Lcobucci\JWT\Signer\Key\InMemory::plainText('biztrack')
        );
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return Services::response()
                ->setJSON(['success' => false, 'message' => 'Missing or invalid token'])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $tokenString = substr($authHeader, 7);
        try {
            $token = $this->config->parser()->parse($tokenString);

            $constraints = [
                new SignedWith($this->config->signer(), $this->config->verificationKey()),
                new ValidAt(SystemClock::fromUTC(), new \DateInterval('PT0S'))
            ];

            if (!$this->config->validator()->validate($token, ...$constraints)) {
                throw new \Exception("Invalid token");
            }

            return;
        } catch (\Exception $e) {
            return Services::response()
                ->setJSON(['success' => false, 'message' => 'Unauthorized: ' . $e->getMessage()])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}