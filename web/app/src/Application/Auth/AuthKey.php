<?php

declare(strict_types=1);

namespace App\Application\Auth;

final readonly class AuthKey implements AuthKeyInterface
{
    public function __construct(
        private string $key,
    ) {}

    public function getKey(): ?string
    {
        return $this->key;
    }
}
