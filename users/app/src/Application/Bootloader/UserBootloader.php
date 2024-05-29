<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Application\PasswordHasher;
use App\Application\UserService;
use App\Domain\User\PasswordHasherInterface;
use App\Domain\User\UserServiceInterface;
use Spiral\Boot\Bootloader\Bootloader;

final class UserBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            PasswordHasherInterface::class => PasswordHasher::class,
            UserServiceInterface::class => UserService::class,
        ];
    }
}
