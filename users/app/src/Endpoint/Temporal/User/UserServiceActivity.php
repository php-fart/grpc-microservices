<?php

declare(strict_types=1);

namespace App\Endpoint\Temporal\User;

use App\Domain\User\UserServiceInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Password;
use Google\Protobuf\Timestamp;
use GRPC\Services\Auth\v1\RegisterRequest;
use GRPC\Services\Users\v1\User;
use Internal\Shared\Temporal\TaskQueue;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[AssignWorker(TaskQueue::UserRegistration)]
#[ActivityInterface(prefix: 'user.service.')]
final class UserServiceActivity
{
    public function __construct(
        private UserServiceInterface $service,
    ) {}

    #[ActivityMethod(name: 'register')] // user.service.register
    public function register(RegisterRequest $request): User
    {
        $user = $this->service->register(
            Email::create($request->getEmail()),
            Password::create($request->getPassword()),
        );

        $createdAt = new Timestamp();
        $createdAt->fromDateTime(\DateTime::createFromInterface($user->createdAt));

        $updatedAt = new Timestamp();
        if ($user->updatedAt !== null) {
            $updatedAt->fromDateTime(\DateTime::createFromInterface($user->updatedAt));
        }

        return new User([
            'uuid' => (string) $user->uuid,
            'name' => (string) $user->profile->fullName(),
            'email' => (string) $user->email,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ]);
    }
}
