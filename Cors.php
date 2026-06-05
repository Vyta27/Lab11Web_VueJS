<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cors extends BaseConfig
{
    public array $default = [
        'allowedOrigins' => ['http://localhost', 'http://localhost:8080'],
        'allowedOriginsPatterns' => ['http://localhost(:\d+)?'],
        'supportsCredentials' => false,
        'allowedHeaders' => ['Content-Type', 'X-Requested-With', 'Authorization'],
        'exposedHeaders' => [],
        'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'maxAge' => 7200,
    ];
}