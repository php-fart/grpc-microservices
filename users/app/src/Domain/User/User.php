<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\Uuid;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(
    repository: UserRepositoryInterface::class,
    table: 'users',
)]
class User
{
    public function __construct(
        #[Column(type: 'uuid', primary: true, name: 'uuid', typecast: 'uuid')]
        public Uuid $uuid,
        #[Column(type: 'string', name: 'email')]
        public Email $email,
        #[Column(type: 'string(64)', name: 'password')]
        public Password $password,
    ) {}
}
