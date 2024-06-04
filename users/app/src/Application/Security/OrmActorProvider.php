<?php

declare(strict_types=1);

namespace App\Application\Security;

use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\Uuid;
use Spiral\Auth\ActorProviderInterface;
use Spiral\Auth\TokenInterface;

final readonly class OrmActorProvider implements ActorProviderInterface
{
    public function __construct(
        private UserRepositoryInterface $users,
    ) {}

    public function getActor(TokenInterface $token): ?object
    {
        $userId = $token->getPayload()['userId'] ?? null;

        if ($userId === null) {
            return null;
        }

        return $this->users->getByUuid(Uuid::fromString($userId));
    }
}
