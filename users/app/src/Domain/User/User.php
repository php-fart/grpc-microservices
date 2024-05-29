<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\PasswordHash;
use App\Domain\User\ValueObject\Uuid;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\Embedded;
use Cycle\ORM\Entity\Behavior\CreatedAt;
use Cycle\ORM\Entity\Behavior\UpdatedAt;

#[Entity(
    role: self::ROLE,
    repository: UserRepositoryInterface::class,
    table: 'users',
)]
#[CreatedAt(field: 'createdAt', column: 'created_at')]
#[UpdatedAt(field: 'updatedAt', column: 'updated_at', nullable: true)]
class User
{
    public const ROLE = 'user';

    #[Embedded(target: Profile::class)]
    public Profile $profile;

    public \DateTimeInterface $createdAt;
    public ?\DateTimeInterface $updatedAt = null;

    public function __construct(
        #[Column(type: 'uuid', name: 'uuid', primary: true, typecast: Uuid::class)]
        public Uuid $uuid,
        #[Column(type: 'string', name: 'email', typecast: Email::class, unique: true)]
        public Email $email,
        #[Column(type: 'string(64)', name: 'password', typecast: PasswordHash::class)]
        public PasswordHash $password,
    ) {
        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }
}
