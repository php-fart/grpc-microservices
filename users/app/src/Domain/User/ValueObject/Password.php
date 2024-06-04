<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use App\Application\Assert;

final readonly class Password implements \Stringable
{
    public static function create(string $password): self
    {
        Assert::minLength($password, 6, 'Password must be at least 6 characters long');
        Assert::maxLength($password, 64, 'Password must be at most 64 characters long');
        Assert::regex($password, '/[0-9]/', 'Password must contain at least one digit');
        Assert::regex($password, '/[A-Z]/', 'Password must contain at least one uppercase letter');
        Assert::regex($password, '/[a-z]/', 'Password must contain at least one lowercase letter');
        Assert::regex($password, '/[^A-Za-z0-9]/', 'Password must contain at least one special character');

        return new self($password);
    }

    final public function __construct(
        private string $password,
    ) {}

    /** Create from data storage raw value */
    final public static function typecast(mixed $value): self
    {
        return new self((string) $value);
    }

    public function __toString(): string
    {
        return $this->password;
    }
}
