<?php

declare(strict_types=1);

return [
    'binaryPath' => directory('root') . 'protoc-gen-php-grpc',
    'generatedPath' => directory('root') . '/generated',
    'namespace' => 'GRPC',
    'services' => [
        directory('root') . '/../proto/users/v1/service.proto',
        directory('root') . '/../proto/auth/v1/service.proto',
        directory('root') . '/../proto/payment/v1/service.proto',
        directory('root') . '/../proto/common/v1/message.proto',
    ],
    'servicesBasePath' => directory('root') . '/../proto',
];
