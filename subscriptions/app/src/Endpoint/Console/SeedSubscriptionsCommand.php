<?php

declare(strict_types=1);

namespace App\Endpoint\Console;

use App\Domain\Subscription\Subscription;
use App\Domain\Subscription\ValueObject\Uuid;
use Cycle\ORM\EntityManagerInterface;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Command;

#[AsCommand(
    name: 'subscription:seed',
    description: 'Create a new subscription'
)]
final class SeedSubscriptionsCommand extends Command
{
    public function __invoke(EntityManagerInterface $em): void
    {
        $em
            ->persist(
                new Subscription(
                    uuid: Uuid::generate(),
                    name: 'Basic',
                    price: 5.0,
                    trialDays: 7,
                ),
            )
            ->persist(
                new Subscription(
                    uuid: Uuid::generate(),
                    name: 'Pro',
                    price: 20.0,
                    trialDays: 0,
                ),
            )
            ->run();
    }
}
