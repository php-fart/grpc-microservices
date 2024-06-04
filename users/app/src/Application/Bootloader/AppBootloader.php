<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Endpoint\GRPC\Service\AuthService;
use GRPC\Services\Auth\v1\AuthServiceInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Core\Container;

final class AppBootloader extends Bootloader
{
    public function boot(Container $container): void
    {
        $container->bind(AuthServiceInterface::class, AuthService::class);
    }
}
