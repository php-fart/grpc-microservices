<?php

declare(strict_types=1);

namespace App\Domain\User;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Embeddable;

#[Embeddable]
class Profile
{
    #[Column(type: 'string', name: 'fist_name', nullable: true)]
    public ?string $fistName = null;

    #[Column(type: 'string', name: 'last_name', nullable: true)]
    public ?string $lastName = null;

    #[Column(type: 'string', name: 'language', default: 'ru')]
    public string $language = 'ru';

    public function fullName(): ?string
    {
        if ($this->fistName === null || $this->lastName === null) {
            return null;
        }

        return \trim($this->fistName . ' ' . $this->lastName);
    }
}
