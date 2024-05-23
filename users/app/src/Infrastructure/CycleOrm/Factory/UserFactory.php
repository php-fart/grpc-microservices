<?php

declare(strict_types=1);

namespace App\Infrastructure\CycleOrm\Factory;

use App\Domain\User\User;
use App\Domain\User\UserFactoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\Uuid;
use Cycle\ORM\ORMInterface;

final readonly class UserFactory implements UserFactoryInterface
{
    public function __construct(
        private ORMInterface $orm,
    ) {}

    public function create(Email $email, Password $password): User
    {
        return $this->orm->make(User::class, [
            'uuid' => Uuid::generate(),
            'email' => $email,
            'password' => $password,
        ]);
    }
}
