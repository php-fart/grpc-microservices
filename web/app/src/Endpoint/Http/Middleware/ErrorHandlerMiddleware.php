<?php

declare(strict_types=1);

namespace App\Endpoint\Http\Middleware;

use App\Application\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (NotFoundException $e) {
            throw new \Spiral\Http\Exception\ClientException\NotFoundException();
        }
    }
}
