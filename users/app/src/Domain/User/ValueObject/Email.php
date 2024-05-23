<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use App\Application\Assert;

final readonly class Email implements \Stringable
{
    public static function create(string $email): self
    {
        Assert::email($email, 'Invalid email address');
        return new self($email);
    }

    private function __construct(
        private string $email,
    ) {}

    /** Create from data storage raw value */
    final public static function typecast(mixed $value): self
    {
        return new self((string) $value);
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
