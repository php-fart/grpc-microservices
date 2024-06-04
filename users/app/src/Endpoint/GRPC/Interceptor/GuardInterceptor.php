<?php

declare(strict_types=1);

namespace App\Endpoint\GRPC\Interceptor;

use App\Endpoint\GRPC\Exception\UnauthorizedException;
use GRPC\Services\Auth\v1\AuthServiceInterface;
use GRPC\Services\Auth\v1\MeRequest;
use GRPC\Services\Users\v1\User;
use Internal\Shared\gRPC\Attribute\Guarded;
use Internal\Shared\gRPC\Request\RequestContext;
use Spiral\Auth\ActorProviderInterface;
use Spiral\Auth\AuthContext;
use Spiral\Auth\AuthContextInterface;
use Spiral\Auth\Session\Token;
use Spiral\Auth\TokenInterface;
use Spiral\Core\Container;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;

final readonly class GuardInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private AuthServiceInterface $authService,
        private Container $container,
    ) {}

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        $refl = new \ReflectionClass($controller);
        $attrs = $refl->getMethod($action)->getAttributes(Guarded::class);

        if (count($attrs) === 0) {
            return $core->callAction($controller, $action, $parameters);
        }

        \assert($parameters['ctx'] instanceof RequestContext);

        $token = $parameters['ctx']->getAuthToken();
        if (!$token) {
            throw new UnauthorizedException('token_missed');
        }

        $user = $this->authService->Me($parameters['ctx'], new MeRequest([
            'token' => $token,
        ]));

        $authContext = new AuthContext(
            $actorProvider = $this->createActorProvider(),
        );

        $actorProvider->setActor($user->getUser());

        $authContext->start(
            token: new Token($token, ['user' => $user->getUser()->getUuid()]),
            transport: 'grpc',
        );

        return $this->container->runScope([
            AuthContextInterface::class => $authContext,
        ], static fn() => $core->callAction($controller, $action, $parameters));
    }

    private function createActorProvider(): ActorProviderInterface
    {
        return new class implements ActorProviderInterface {
            private ?User $actor = null;

            public function getActor(TokenInterface $token): ?object
            {
                return $this->actor;
            }

            public function setActor(User $actor): void
            {
                $this->actor = $actor;
            }
        };
    }
}
