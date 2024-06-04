<?php

namespace GRPC\Services\Auth\v1;

use Internal\Shared\gRPC\Request\RequestContext;
use Spiral\RoadRunner\GRPC;

interface AuthServiceInterface extends GRPC\ServiceInterface
{
    public const NAME = 'auth.v1.AuthService';

    /**
     * @param RequestContext $ctx
     */
    public function Login(GRPC\ContextInterface $ctx, LoginRequest $in): LoginResponse;

    /**
     * @param RequestContext $ctx
     */
    public function Logout(GRPC\ContextInterface $ctx, LogoutRequest $in): \GRPC\Services\Common\v1\PBEmpty;

    /**
     * @param RequestContext $ctx
     */
    public function Register(GRPC\ContextInterface $ctx, RegisterRequest $in): RegisterResponse;

    /**
     * @param RequestContext $ctx
     */
    public function Me(GRPC\ContextInterface $ctx, MeRequest $in): MeResponse;
}
