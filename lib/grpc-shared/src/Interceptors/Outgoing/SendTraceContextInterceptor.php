<?php

declare(strict_types=1);

namespace Internal\Shared\gRPC\Interceptors\Outgoing;

use Internal\Shared\gRPC\Request\RequestContext;
use Spiral\Core\Attribute\Proxy;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\Telemetry\TraceKind;
use Spiral\Telemetry\TracerInterface;

final readonly class SendTraceContextInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        #[Proxy] private TracerInterface $tracer,
    ) {}

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        $context = $parameters['ctx'];
        \assert($context instanceof RequestContext);

        $parameters['ctx'] = $context->withTelemetryContext($this->tracer->getContext());

        return $this->tracer->trace(
            name: \sprintf('GRPC request %s', $action),
            callback: static fn() => $core->callAction($controller, $action, $parameters),
            attributes: [
                'controller' => $controller,
                'action' => $action,
            ],
            traceKind: TraceKind::CLIENT,
        );
    }
}