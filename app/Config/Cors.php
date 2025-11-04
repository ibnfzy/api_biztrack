<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cors extends BaseConfig
{
    public array $default = [
        // Izinkan semua origin (untuk produksi lebih baik dibatasi)
        'allowedOrigins' => ['*'],

        // Atau kalau mau regex (misalnya hanya subdomain tertentu)
        'allowedOriginsPatterns' => [],

        // Kalau perlu kirim cookies / Authorization header
        'supportsCredentials' => true,

        // Header yang diizinkan
        'allowedHeaders' => [
            'Content-Type',
            'Authorization',
            'X-Requested-With',
            'Accept',
            'Origin'
        ],

        // Header yang boleh diekspos ke client
        'exposedHeaders' => [
            'Authorization',
            'Content-Disposition'
        ],

        // Method yang diizinkan
        'allowedMethods' => [
            'GET',
            'POST',
            'PUT',
            'PATCH',
            'DELETE',
            'OPTIONS'
        ],

        // Waktu cache preflight (OPTIONS)
        'maxAge' => 7200,
    ];
}