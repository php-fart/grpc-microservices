<?php

declare(strict_types=1);

namespace App\Endpoint\Http\Controller\User;

use GRPC\Services\Users\v1\UsersServiceInterface;
use Internal\Shared\Request\RequestContext;
use Ramsey\Uuid\Uuid;
use Spiral\RoadRunner\GRPC\Context;
use Spiral\Router\Annotation\Route;

final class ShowAction
{
    #[Route(route: '/user/<uuid>', methods: ['GET'])]
    public function __invoke(
        UsersServiceInterface $users,
        string $uuid,
    ): array {
        $response = $users->Get(
            RequestContext::create()
                ->withAuthToken('1234567890'),
            new \GRPC\Services\Users\v1\GetRequest([
                'uuid' => Uuid::fromString($uuid)->toString(),
            ]),
        );

        return \json_decode(
            $response->getUser()->serializeToJsonString(),
            true,
        );
    }
}
