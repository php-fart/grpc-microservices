<?php

declare(strict_types=1);

namespace App\Endpoint\GRPC\Service;

use App\Application\Exception\UserNotFoundException;
use GRPC\Services\Users\v1\User;
use GRPC\Services\Users\v1\UsersServiceInterface;
use Ramsey\Uuid\Uuid;
use Spiral\RoadRunner\GRPC;

final class UserService implements UsersServiceInterface
{
    public function List(
        GRPC\ContextInterface $ctx,
        \GRPC\Services\Users\v1\ListRequest $in,
    ): \GRPC\Services\Users\v1\ListResponse {
        return new \GRPC\Services\Users\v1\ListResponse();
    }

    public function Get(
        GRPC\ContextInterface $ctx,
        \GRPC\Services\Users\v1\GetRequest $in,
    ): \GRPC\Services\Users\v1\GetResponse {

        throw new UserNotFoundException();
    }

    public function Create(
        GRPC\ContextInterface $ctx,
        \GRPC\Services\Users\v1\CreateRequest $in,
    ): \GRPC\Services\Users\v1\CreateResponse {
        $user = new User([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $in->getUser()->getName(),
            'email' => $in->getUser()->getEmail(),
        ]);

        $timestamp = new \Google\Protobuf\Timestamp();
        $timestamp->fromDateTime(new \DateTime());
        $user->setCreatedAt($timestamp);
        $user->setUpdatedAt($timestamp);

        return new \GRPC\Services\Users\v1\CreateResponse([
            'user' => $user,
        ]);
    }
}
