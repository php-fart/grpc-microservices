<?php

declare(strict_types=1);

namespace App\Application\GRPC\Interceptor;

use App\Application\Auth\AuthKeyInterface;
use Internal\Shared\gRPC\Request\RequestContext;
use Spiral\Core\Attribute\Proxy;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;

final class AuthInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        #[Proxy] public AuthKeyInterface $key,
    ) {}

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        /** @var RequestContext $context */
        $context = $parameters['ctx'];
        $parameters['ctx'] = $context->withAuthToken($this->key->getKey());

        return $core->callAction($controller, $action, $parameters);
    }
}
