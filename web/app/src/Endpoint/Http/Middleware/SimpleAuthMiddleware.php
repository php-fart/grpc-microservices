<?php

declare(strict_types=1);

namespace App\Endpoint\Http\Middleware;

use App\Application\Auth\AuthKey;
use App\Application\Auth\AuthKeyInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spiral\Core\Container;

final readonly class SimpleAuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Container $container,
    ) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $authKey = $request->getHeaderLine('X-Auth-Token') ?? null;

        return $this->container->runScope([
            AuthKeyInterface::class => new AuthKey($authKey),
        ], function () use ($request, $handler) {
            return $handler->handle($request);
        });
    }
}
