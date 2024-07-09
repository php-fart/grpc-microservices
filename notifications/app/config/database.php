<?php

declare(strict_types=1);

use Cycle\Database\Config;

return [
    'logger' => [
        'default' => null,
        'drivers' => [
            // 'runtime' => 'stdout'
        ],
    ],

    'default' => 'default',

    'databases' => [
        'default' => [
            'driver' => env('DB_CONNECTION', 'pgsql'),
        ],
    ],

    'drivers' => [
        'pgsql' => new Config\PostgresDriverConfig(
            connection: new Config\Postgres\TcpConnectionConfig(
                database: env('DB_DATABASE', 'spiral'),
                host: env('DB_HOST', '127.0.0.1'),
                port: (int) env('DB_PORT', 5432),
                user: env('DB_USERNAME', 'postgres'),
                password: env('DB_PASSWORD', ''),
            ),
            schema: 'public',
            queryCache: true,
        ),
    ],
];
