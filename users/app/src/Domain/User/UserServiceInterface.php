<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\Uuid;

interface UserServiceInterface
{
    public function register(Email $email, Password $password): User;

    public function login(Email $email, string $password): void;

    public function logout(string $token): void;

    public function getUser(Uuid $uuid): User;
}
