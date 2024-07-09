<?php

declare(strict_types=1);

namespace App\Domain\Subscription;

use App\Domain\Subscription\ValueObject\Uuid;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\ORM\Entity\Behavior\CreatedAt;
use Cycle\ORM\Entity\Behavior\UpdatedAt;

#[Entity(
    role: self::ROLE,
    repository: SubscriptionRepositoryInterface::class,
    table: 'subscriptions',
)]
#[CreatedAt(field: 'createdAt', column: 'created_at')]
#[UpdatedAt(field: 'updatedAt', column: 'updated_at', nullable: true)]
class Subscription
{
    public const ROLE = 'subscription';

    public \DateTimeInterface $createdAt;
    public ?\DateTimeInterface $updatedAt = null;

    public function __construct(
        #[Column(type: 'uuid', name: 'uuid', primary: true, typecast: Uuid::class)]
        public Uuid $uuid,
        #[Column(type: 'string', name: 'name', unique: true)]
        public string $name,
        #[Column(type: 'double', name: 'price')]
        public float $price,
        #[Column(type: 'integer', name: 'trial_days')]
        public int $trialDays = 0,
    ) {
        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }
}
