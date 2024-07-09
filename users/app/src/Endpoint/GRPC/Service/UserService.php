<?php

declare(strict_types=1);

namespace App\Endpoint\GRPC\Service;

use App\Domain\User\UserServiceInterface;
use App\Domain\User\ValueObject\Uuid;
use App\Endpoint\GRPC\Mapper\UserService\UserMapper;
use GRPC\Services\Users\v1\CreateRequest;
use GRPC\Services\Users\v1\CreateResponse;
use GRPC\Services\Users\v1\GetRequest;
use GRPC\Services\Users\v1\GetResponse;
use GRPC\Services\Users\v1\UpdateRequest;
use GRPC\Services\Users\v1\UpdateResponse;
use GRPC\Services\Users\v1\UsersServiceInterface;
use Internal\Shared\gRPC\Attribute\Guarded;
use Spiral\Auth\AuthContextInterface;
use Spiral\Core\Attribute\Proxy;
use Spiral\RoadRunner\GRPC;

final readonly class UserService implements UsersServiceInterface
{
    public function __construct(
        private UserServiceInterface $usersService,
        private UserMapper $userMapper,
        #[Proxy] private AuthContextInterface $authContext,
    ) {}

    #[Guarded]
    public function Get(GRPC\ContextInterface $ctx, GetRequest $in): GetResponse
    {
//        $user = $this->usersService->getUser(Uuid::fromString($in->getUuid()));

        return new GetResponse();
    }

    #[Guarded]
    public function Create(GRPC\ContextInterface $ctx, CreateRequest $in): CreateResponse
    {
        // TODO: Implement Create() method.
    }

    #[Guarded]
    public function Update(GRPC\ContextInterface $ctx, UpdateRequest $in): UpdateResponse
    {
        // TODO: Implement Update() method.
    }
}
