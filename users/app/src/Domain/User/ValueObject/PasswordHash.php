<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

final readonly class PasswordHash implements \Stringable
{
    public function __construct(
        private string $hash,
    ) {}

    /** Create from data storage raw value */
    final public static function typecast(mixed $value): self
    {
        return new self((string) $value);
    }

    public function __toString(): string
    {
        return $this->hash;
    }
}
