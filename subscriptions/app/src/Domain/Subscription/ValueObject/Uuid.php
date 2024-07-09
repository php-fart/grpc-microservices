<?php

declare(strict_types=1);

namespace App\Domain\Subscription\ValueObject;

use Ramsey\Uuid\UuidInterface;
use Temporal\Internal\Marshaller\Meta\Marshal;

final readonly class Uuid implements \Stringable
{
    public static function generate(): self
    {
        return new self(\Ramsey\Uuid\Uuid::uuid7());
    }

    public static function fromString(string $uuid): self
    {
        return new self(\Ramsey\Uuid\Uuid::fromString($uuid));
    }

    public function __construct(
        #[Marshal]
        private UuidInterface $uuid,
    ) {}

    /** Create from data storage raw value */
    final public static function typecast(mixed $value): self
    {
        return self::fromString($value);
    }

    public function __toString()
    {
        return (string) $this->uuid;
    }
}
