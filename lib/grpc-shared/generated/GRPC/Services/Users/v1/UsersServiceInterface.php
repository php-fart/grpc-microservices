<?php

namespace GRPC\Services\Users\v1;

use Internal\Shared\gRPC\Request\RequestContext;
use Spiral\RoadRunner\GRPC;

interface UsersServiceInterface extends GRPC\ServiceInterface
{
    public const NAME = 'users.v1.UsersService';

    /**
     * @param RequestContext $ctx
     */
    public function Get(GRPC\ContextInterface $ctx, GetRequest $in): GetResponse;

    /**
     * @param RequestContext $ctx
     */
    public function Create(GRPC\ContextInterface $ctx, CreateRequest $in): CreateResponse;

    /**
     * @param RequestContext $ctx
     */
    public function Update(GRPC\ContextInterface $ctx, UpdateRequest $in): UpdateResponse;
}
