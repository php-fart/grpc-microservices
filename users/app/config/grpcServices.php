<?php

declare(strict_types=1);

return [
    'interceptors' => [
        \Internal\Shared\gRPC\Interceptors\Outgoing\SendTraceContextInterceptor::class,
    ],
];
