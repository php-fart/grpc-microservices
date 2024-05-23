<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Ramsey\Uuid\UuidInterface;

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
        private UuidInterface $uuid,
    ) {}

    /** Create from data storage raw value */
    final public static function typecast(mixed $value): self
    {
        return self::fromString($value);
    }

    public function __toString()
    {
        return $this->uuid->toString();
    }
}
