<?php

declare(strict_types=1);

namespace GRPC\Services\Users\v1;

use Spiral\Core\InterceptableCore;
use Spiral\RoadRunner\GRPC\ContextInterface;

class UsersServiceClient implements UsersServiceInterface
{
    public function __construct(
        private readonly InterceptableCore $core,
    ) {
    }

    public function Get(ContextInterface $ctx, GetRequest $in): GetResponse
    {
        [$response, $status] = $this->core->callAction(UsersServiceInterface::class, '/'.self::NAME.'/Get', [
            'in' => $in,
            'ctx' => $ctx,
            'responseClass' => \GRPC\Services\Users\v1\GetResponse::class,
        ]);

        return $response;
    }

    public function Create(ContextInterface $ctx, CreateRequest $in): CreateResponse
    {
        [$response, $status] = $this->core->callAction(UsersServiceInterface::class, '/'.self::NAME.'/Create', [
            'in' => $in,
            'ctx' => $ctx,
            'responseClass' => \GRPC\Services\Users\v1\CreateResponse::class,
        ]);

        return $response;
    }

    public function Update(ContextInterface $ctx, UpdateRequest $in): UpdateResponse
    {
        [$response, $status] = $this->core->callAction(UsersServiceInterface::class, '/'.self::NAME.'/Update', [
            'in' => $in,
            'ctx' => $ctx,
            'responseClass' => \GRPC\Services\Users\v1\UpdateResponse::class,
        ]);

        return $response;
    }
}
