<?php

declare(strict_types=1);

namespace App\Domain\User\Specification;

use App\Domain\User\ValueObject\Email;

interface UniqueEmailSpecificationInterface
{
    public function isSatisfiedBy(Email $email): bool;
}
