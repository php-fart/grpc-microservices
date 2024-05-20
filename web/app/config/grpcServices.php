<?php

declare(strict_types=1);

return [
    'interceptors' => [
        \App\Application\GRPC\Interceptor\AuthInterceptor::class,
        \App\Application\GRPC\Interceptor\ExceptionHandlerInterceptor::class,
    ],
];
