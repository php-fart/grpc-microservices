<?php

declare(strict_types=1);

namespace App\Endpoint\Http\Controller\User;

use App\Endpoint\Http\Request\User\CreateRequest;
use GRPC\Services\Users\v1\CreateRequest\User;
use GRPC\Services\Users\v1\UsersServiceInterface;
use Spiral\RoadRunner\GRPC\Context;
use Spiral\Router\Annotation\Route;

final readonly class CreateAction
{
    #[Route(route: '/user', methods: ['POST'])]
    public function __invoke(
        UsersServiceInterface $users,
        CreateRequest $request,
    ): array {
        $response = $users->Create(
            new Context([]),
            new \GRPC\Services\Users\v1\CreateRequest([
                'user' => new User([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password,
                ]),
            ]),
        );

        return \json_decode(
            $response->getUser()->serializeToJsonString(),
            true,
        );
    }
}
