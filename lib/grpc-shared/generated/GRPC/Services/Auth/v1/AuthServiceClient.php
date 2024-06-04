<?php

declare(strict_types=1);

namespace GRPC\Services\Auth\v1;

use Spiral\Core\InterceptableCore;
use Spiral\RoadRunner\GRPC\ContextInterface;

class AuthServiceClient implements AuthServiceInterface
{
    public function __construct(
        private readonly InterceptableCore $core,
    ) {
    }

    public function Login(ContextInterface $ctx, LoginRequest $in): LoginResponse
    {
        [$response, $status] = $this->core->callAction(AuthServiceInterface::class, '/'.self::NAME.'/Login', [
            'in' => $in,
            'ctx' => $ctx,
            'responseClass' => \GRPC\Services\Auth\v1\LoginResponse::class,
        ]);

        return $response;
    }

    public function Logout(ContextInterface $ctx, LogoutRequest $in): \GRPC\Services\Common\v1\PBEmpty
    {
        [$response, $status] = $this->core->callAction(AuthServiceInterface::class, '/'.self::NAME.'/Logout', [
            'in' => $in,
            'ctx' => $ctx,
            'responseClass' => \GRPC\Services\Common\v1\PBEmpty::class,
        ]);

        return $response;
    }

    public function Register(ContextInterface $ctx, RegisterRequest $in): RegisterResponse
    {
        [$response, $status] = $this->core->callAction(AuthServiceInterface::class, '/'.self::NAME.'/Register', [
            'in' => $in,
            'ctx' => $ctx,
            'responseClass' => \GRPC\Services\Auth\v1\RegisterResponse::class,
        ]);

        return $response;
    }

    public function Me(ContextInterface $ctx, MeRequest $in): MeResponse
    {
        [$response, $status] = $this->core->callAction(AuthServiceInterface::class, '/'.self::NAME.'/Me', [
            'in' => $in,
            'ctx' => $ctx,
            'responseClass' => \GRPC\Services\Auth\v1\MeResponse::class,
        ]);

        return $response;
    }
}
