<?php

declare(strict_types=1);

namespace App\Application\Auth;

interface AuthKeyInterface
{
    public function getKey(): ?string;
}
