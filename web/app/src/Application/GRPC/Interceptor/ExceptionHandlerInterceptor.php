<?php

declare(strict_types=1);

namespace App\Application\GRPC\Interceptor;

use App\Application\Exception\NotFoundException;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\RoadRunner\GRPC\StatusCode;

final class ExceptionHandlerInterceptor implements CoreInterceptorInterface
{
    public function process(
        string $controller,
        string $action,
        array $parameters,
        CoreInterface $core,
    ): mixed {
        $response = $core->callAction($controller, $action, $parameters);

        $statusCode = (int) ($response[1]?->code ?? StatusCode::UNKNOWN);

        if ($statusCode === StatusCode::OK) {
            return $response;
        }

        match ($response[1]->details) {
            'users.user_not_found' => throw new NotFoundException(),
        };
    }
}
