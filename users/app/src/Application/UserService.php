<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Exception\EmailAlreadyExistsException;
use App\Application\Exception\PasswordIncorrectException;
use App\Application\Exception\UserNotFoundException;
use App\Domain\User\PasswordHasherInterface;
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
        private PasswordHasherInterface $passwordHasher,
        private UserRepositoryInterface $users,
        private UniqueEmailSpecificationInterface $uniqueEmail,
        private EntityManagerInterface $em,
    ) {}

    public function register(Email $email, Password $password): User
    {
        if (!$this->uniqueEmail->isSatisfiedBy($email)) {
            throw new EmailAlreadyExistsException();
        }

        $user = $this->userFactory->create($email, $password);
        $this->em->persist($user)->run();

        return $user;
    }

    public function login(Email $email, Password $password): User
    {
        $user = $this->users->findByEmail($email);

        if (!$user) {
            throw new UserNotFoundException();
        }

        // Check is user active
        // Check is banned
        // Check is deleted
        // Check is email confirmed

        if (!$this->passwordHasher->validate($password, $user->password)) {
            throw new PasswordIncorrectException();
        }

        return $user;
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
