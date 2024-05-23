<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Exception\EmailAlreadyExistsException;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\User;
use App\Domain\User\UserFactoryInterface;
use App\Domain\User\UserRepositoryInterface;
use App\Domain\User\UserServiceInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\Uuid;
use Cycle\ORM\EntityManagerInterface;

final readonly class UserService implements UserServiceInterface
{
    public function __construct(
        private UserFactoryInterface $userFactory,
        private UserRepositoryInterface $users,
        private UniqueEmailSpecificationInterface $uniqueEmail,
        private EntityManagerInterface $em,
    ) {}

    public function register(Email $email, Password $password): User
    {
        $this->uniqueEmail->isSatisfiedBy($email)
        or throw new EmailAlreadyExistsException();

        $user = $this->userFactory->create($email, $password);
        $this->em->persist($user)->run();

        return $user;
    }

    public function login(Email $email, string $password): void
    {
        // TODO: Implement login() method.
    }

    public function logout(string $token): void
    {
        // TODO: Implement logout() method.
    }

    public function getUser(Uuid $uuid): User
    {
        return $this->users->getByUuid($uuid);
    }
}
