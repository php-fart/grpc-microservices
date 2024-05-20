<?php

declare(strict_types=1);

namespace App\Application\GRPC\Interceptor;

use App\Application\Auth\AuthKeyInterface;
use Spiral\Core\Attribute\Proxy;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\RoadRunner\GRPC\Context;

final class AuthInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        #[Proxy] public AuthKeyInterface $key,
    ) {}

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        /** @var Context $context */
        $context = $parameters['ctx'];
        $metadata = $context->getValue('metadata') ?? [];
        $metadata['auth-key'] = [$this->key->getKey()];
        $parameters['ctx'] = $context->withValue('metadata', $metadata);

        return $core->callAction($controller, $action, $parameters);
    }
}
