<?php

declare(strict_types=1);

namespace Internal\Shared\Interceptors\Incoming;

use App\Application\Exception\NotFoundException;
use Google\Rpc\Status;
use GRPC\ProtobufMetadata\Common\v1\Message;
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

        Message::initOnce();

        $status = new Status();
        $status->mergeFromString($response[1]->metadata['grpc-status-details-bin'][0]);

        // TODO: use exception DTO
        match ($response[1]->details) {
            'users.user_not_found' => throw new NotFoundException(),
        };
    }
}
