<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\User;
use App\Domain\User\UserFactoryInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Infrastructure\CycleOrm\Factory\UserFactory;
use App\Infrastructure\CycleOrm\Repository\UserRepository;
use App\Infrastructure\CycleOrm\Specification\UniqueEmailSpecification;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use Spiral\Boot\Bootloader\Bootloader;

final class PersistenceBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            UserRepositoryInterface::class => static fn(
                ORMInterface $orm,
            ): UserRepositoryInterface => new UserRepository(new Select($orm, User::ROLE)),

            UserFactoryInterface::class => UserFactory::class,

            UniqueEmailSpecificationInterface::class => UniqueEmailSpecification::class,
        ];
    }
}
