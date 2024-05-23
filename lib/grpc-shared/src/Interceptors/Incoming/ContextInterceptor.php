<?php

declare(strict_types=1);

namespace Internal\Shared\Interceptors\Incoming;

use Internal\Shared\Request\RequestContext;
use Spiral\Core\Container;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\RoadRunner\GRPC\ContextInterface;

final readonly class ContextInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private Container $container,
    ) {}

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        trap($parameters['ctx']);
        $parameters['ctx'] = new RequestContext($parameters['ctx']);

        return $this->container->runScope([
            ContextInterface::class => $parameters['ctx'],
        ], static fn() => $core->callAction($controller, $action, $parameters));
    }
}