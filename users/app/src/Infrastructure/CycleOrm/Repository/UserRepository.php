<?php

declare(strict_types=1);

namespace App\Infrastructure\CycleOrm\Repository;

use App\Application\Exception\UserNotFoundException;
use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Uuid;
use Cycle\ORM\Select\Repository;

final class UserRepository extends Repository implements UserRepositoryInterface
{
    public function findByEmail(Email $email): ?User
    {
        return $this->select()->where('email', $email)->fetchOne();
    }

    public function findByUuid(Uuid $uuid): ?User
    {
        return $this->select()->where('uuid', $uuid)->fetchOne();
    }

    public function getByEmail(Email $email): User
    {
        return $this->findByEmail($email) ?? throw new UserNotFoundException();
    }

    public function getByUuid(Uuid $uuid): User
    {
        return $this->findByUuid($uuid) ?? throw new UserNotFoundException();
    }
}
