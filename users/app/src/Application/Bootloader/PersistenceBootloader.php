<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\UserFactoryInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\CycleOrm\Factory\UserFactory;
use App\Infrastructure\CycleOrm\Repository\UserRepository;
use App\Infrastructure\CycleOrm\Specification\UniqueEmailSpecification;
use Spiral\Boot\Bootloader\Bootloader;

final class PersistenceBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            UserRepositoryInterface::class => UserRepository::class,

            UserFactoryInterface::class => UserFactory::class,

            UniqueEmailSpecificationInterface::class => UniqueEmailSpecification::class,
        ];
    }
}
