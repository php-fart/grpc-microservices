<?php

declare(strict_types=1);

namespace Internal\Shared\Interceptors\Incoming;

use Internal\Shared\Request\RequestContext;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\Telemetry\TraceKind;
use Spiral\Telemetry\TracerFactoryInterface;

final readonly class OpenTelemetryInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private TracerFactoryInterface $tracerFactory,
    ) {}

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        $context = $parameters['ctx'];
        \assert($context instanceof RequestContext);

        $telemetryContext = $context->getTelemetry();

        return $this->tracerFactory
            ->make($telemetryContext)
            ->trace(
                name: \sprintf('Incoming GRPC %s::%s', $controller, $action),
                callback: static fn() => $core->callAction($controller, $action, $parameters),
                attributes: [
                    'controller' => $controller,
                    'action' => $action,
                ],
                scoped: true,
                traceKind: TraceKind::SERVER,
            );
    }
}
