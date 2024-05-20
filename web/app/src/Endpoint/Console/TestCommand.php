<?php

declare(strict_types=1);

namespace App\Endpoint\Console;

use GRPC\Services\Users\v1\UsersServiceInterface;
use Ramsey\Uuid\Uuid;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Command;
use Spiral\RoadRunner\GRPC\Context;

#[AsCommand(
    name: 'test',
    description: 'Test command',
)]
final class TestCommand extends Command
{
    public function __invoke(UsersServiceInterface $users): int
    {
        $response = $users->Get(
            new Context([]),
            new \GRPC\Services\Users\v1\GetRequest([
                'uuid' => Uuid::uuid4()->toString()
            ])
        );

        trap($response);

        return 0;
    }
}
