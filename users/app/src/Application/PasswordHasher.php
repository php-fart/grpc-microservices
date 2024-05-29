<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\User\PasswordHasherInterface;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\PasswordHash;

final class PasswordHasher implements PasswordHasherInterface
{
    public function hash(Password $password): PasswordHash
    {
        return new PasswordHash(
            \password_hash(password: (string) $password, algo: PASSWORD_DEFAULT),
        );
    }

    public function validate(Password $password, PasswordHash $hash): bool
    {
        return \password_verify((string) $password, (string) $hash);
    }
}
