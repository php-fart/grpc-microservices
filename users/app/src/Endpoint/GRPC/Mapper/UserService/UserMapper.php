<?php

declare(strict_types=1);

namespace App\Endpoint\GRPC\Mapper\UserService;

use App\Domain\User\User;
use App\Endpoint\GRPC\Mapper\TimestampMapper;

final readonly class UserMapper
{
    public function __construct(
        private TimestampMapper $timestamps,
    ) {}

    public function toMessage(User $user): \GRPC\Services\Users\v1\User
    {
        $data = [
            'uuid' => (string) $user->uuid,
            'email' => (string) $user->email,
            'name' => (string) $user->profile->fullName(),
            'created_at' => $this->timestamps->toMessage($user->createdAt),
//            'updated_at' => $this->timestamps->toMessage($user->updatedAt),
        ];

        $updatedAt = $this->timestamps->toMessage($user->updatedAt);

        if ($updatedAt !== null) {
            $data['updated_at'] = $updatedAt;
        }

        return new \GRPC\Services\Users\v1\User($data);
    }
}
