<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Application\Security\OrmActorProvider;
use Spiral\Auth\ActorProviderInterface;
use Spiral\Boot\Bootloader\Bootloader;

final class AuthBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            ActorProviderInterface::class => OrmActorProvider::class
        ];
    }
}
