<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Uuid;
use Cycle\ORM\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail(Email $email): ?User;

    public function getByEmail(Email $email): User;

    public function findByUuid(Uuid $uuid): ?User;

    public function getByUuid(Uuid $uuid): User;
}
