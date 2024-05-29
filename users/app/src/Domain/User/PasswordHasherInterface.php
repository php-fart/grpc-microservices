<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\PasswordHash;

interface PasswordHasherInterface
{
    public function hash(Password $password): PasswordHash;

    public function validate(Password $password, PasswordHash $hash): bool;
}
