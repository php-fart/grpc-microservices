<?php
# Generated by the protocol buffer compiler (roadrunner-server/grpc). DO NOT EDIT!
# source: users/v1/service.proto

namespace GRPC\Services\Users\v1;

use Spiral\RoadRunner\GRPC;

interface UsersServiceInterface extends GRPC\ServiceInterface
{
    // GRPC specific service name.
    public const NAME = "users.v1.UsersService";

    /**
    * @param GRPC\ContextInterface $ctx
    * @param \GRPC\Services\Users\v1\ListRequest $in
    * @return \GRPC\Services\Users\v1\ListResponse
    *
    * @throws GRPC\Exception\InvokeException
    */
    public function List(GRPC\ContextInterface $ctx, \GRPC\Services\Users\v1\ListRequest $in): \GRPC\Services\Users\v1\ListResponse;

    /**
    * @param GRPC\ContextInterface $ctx
    * @param \GRPC\Services\Users\v1\GetRequest $in
    * @return \GRPC\Services\Users\v1\GetResponse
    *
    * @throws GRPC\Exception\InvokeException
    */
    public function Get(GRPC\ContextInterface $ctx, \GRPC\Services\Users\v1\GetRequest $in): \GRPC\Services\Users\v1\GetResponse;

    /**
    * @param GRPC\ContextInterface $ctx
    * @param \GRPC\Services\Users\v1\CreateRequest $in
    * @return \GRPC\Services\Users\v1\CreateResponse
    *
    * @throws GRPC\Exception\InvokeException
    */
    public function Create(GRPC\ContextInterface $ctx, \GRPC\Services\Users\v1\CreateRequest $in): \GRPC\Services\Users\v1\CreateResponse;
}