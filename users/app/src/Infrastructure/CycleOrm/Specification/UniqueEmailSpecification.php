<?php

declare(strict_types=1);

namespace App\Infrastructure\CycleOrm\Specification;

use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\ValueObject\Email;

final readonly class UniqueEmailSpecification implements UniqueEmailSpecificationInterface
{
    public function __construct(
        private UserRepositoryInterface $users,
    ) {}

    public function isSatisfiedBy(Email $email): bool
    {
        return $this->users->findByEmail($email) === null;
    }
}
