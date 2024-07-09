<?php

declare(strict_types=1);

namespace App\Domain\Subscription;

use App\Domain\Subscription\ValueObject\Uuid;

interface SubscriptionRepositoryInterface
{
    public function findByUuid(Uuid $uuid): ?Subscription;

    public function getByUuid(Uuid $uuid): Subscription;
}
