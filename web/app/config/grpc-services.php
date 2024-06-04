<?php

declare(strict_types=1);

return [
    'interceptors' => [
        \Internal\Shared\gRPC\Interceptors\Outgoing\SendTraceContextInterceptor::class,
        \App\Application\GRPC\Interceptor\AuthInterceptor::class,
        \Internal\Shared\gRPC\Interceptors\Incoming\ExceptionHandlerInterceptor::class,
    ],
];
