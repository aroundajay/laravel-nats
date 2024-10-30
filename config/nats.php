<?php

use Basis\Nats\Configuration;

return [
    'connections' => [
        'default' => [
            'host' => env('NATS_HOST', 'localhost'),
            'port' => (int)env('NATS_PORT', 4222),
            'jwt' => env('NATS_JWT'),
            'pass' => env('NATS_PASS'),
            'user' => env('NATS_USER'),
            'pedantic' => false,
            'timeout' => 1,

            'delay' => [
                'seconds' => 0.001,
                'mode' => Configuration::DELAY_CONSTANT,
            ]
        ],
    ],
];
