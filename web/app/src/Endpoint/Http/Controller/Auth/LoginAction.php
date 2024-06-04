<?php

declare(strict_types=1);

namespace App\Endpoint\Http\Controller\Auth;

use App\Endpoint\Http\Request\Auth\LoginRequest;
use GRPC\Services\Auth\v1\AuthServiceInterface;
use Internal\Shared\gRPC\Request\RequestContext;
use Spiral\Router\Annotation\Route;

final readonly class LoginAction
{
    public function __construct(
        private AuthServiceInterface $authService,
    ) {}

    #[Route('/login', methods: ['POST'])]
    public function __invoke(LoginRequest $request): array
    {
        $response = $this->authService->Login(
            RequestContext::create(),
            new \GRPC\Services\Auth\v1\LoginRequest([
                'email' => $request->email,
                'password' => $request->password,
            ]),
        );

        return \json_decode(
            $response->getToken()->serializeToJsonString(),
            true,
        );
    }
}
