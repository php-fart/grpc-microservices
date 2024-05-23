<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;

interface UserFactoryInterface
{
    public function create(Email $email, Password $password): User;
}
