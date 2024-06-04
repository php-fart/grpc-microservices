<?php

declare(strict_types=1);

namespace App\Endpoint\GRPC\Service;

use App\Domain\User\UserServiceInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Endpoint\GRPC\Exception\UnauthorizedException;
use App\Endpoint\GRPC\Mapper\UserService\UserMapper;
use Carbon\Carbon;
use Google\Protobuf\Timestamp;
use GRPC\Services\Auth\v1\AuthServiceInterface;
use GRPC\Services\Auth\v1\LoginRequest;
use GRPC\Services\Auth\v1\LoginResponse;
use GRPC\Services\Auth\v1\LogoutRequest;
use GRPC\Services\Auth\v1\MeRequest;
use GRPC\Services\Auth\v1\MeResponse;
use GRPC\Services\Auth\v1\RegisterRequest;
use GRPC\Services\Auth\v1\RegisterResponse;
use GRPC\Services\Auth\v1\Token;
use Spiral\Auth\ActorProviderInterface;
use Spiral\Auth\TokenStorageInterface;
use Spiral\RoadRunner\GRPC;

final readonly class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserServiceInterface $userService,
        private TokenStorageInterface $tokenService,
        private ActorProviderInterface $provider,
        private UserMapper $mapper,
    ) {}

    public function Login(
        GRPC\ContextInterface $ctx,
        LoginRequest $in,
    ): LoginResponse {
        $user = $this->userService->login(
            Email::create($in->getEmail()),
            new Password($in->getPassword()),
        );

        $token = $this->tokenService->create(
            ['userId' => (string) $user->uuid],
            Carbon::now()->addWeek(),
        );

        $expiresAt = new Timestamp();
        $expiresAt->fromDateTime(\DateTime::createFromInterface($token->getExpiresAt()));

        return new LoginResponse([
            'token' => new Token([
                'token' => $token->getID(),
                'type' => 'auth',
                'expires_at' => $expiresAt,
            ]),
        ]);
    }

    public function Logout(
        GRPC\ContextInterface $ctx,
        LogoutRequest $in,
    ): \GRPC\Services\Common\v1\PBEmpty {
        // TODO: Implement Logout() method.
    }

    public function Register(GRPC\ContextInterface $ctx, RegisterRequest $in): RegisterResponse
    {
        // TODO: Implement Register() method.
    }

    public function Me(GRPC\ContextInterface $ctx, MeRequest $in): MeResponse
    {
        // todo: throw an exception
        \assert($in->getToken() !== null);

        $token = $this->tokenService->load($in->getToken());
        if (!$token) {
            throw new UnauthorizedException('token_not_found');
        }

        $user = $this->provider->getActor($token);

        return new MeResponse([
            'user' => $this->mapper->toMessage($user),
        ]);
    }
}
