<?php

declare(strict_types=1);

return [
    'interceptors' => [
        \Internal\Shared\Interceptors\Outgoing\SendTraceContextInterceptor::class,
    ],
];
