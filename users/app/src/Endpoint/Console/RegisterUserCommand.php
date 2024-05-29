<?php

declare(strict_types=1);

namespace App\Endpoint\Console;

use App\Endpoint\Temporal\User\RegisterWorkflow;
use GRPC\Services\Auth\v1\RegisterRequest;
use GRPC\Services\Users\v1\User;
use Spiral\Console\Attribute\Argument;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Command;
use Temporal\Client\WorkflowClientInterface;
use Temporal\Client\WorkflowOptions;

#[AsCommand(
    name: 'user:register',
    description: 'Register a new user'
)]
final class RegisterUserCommand extends Command
{
    #[Argument(name: 'email', description: 'User email')]
    public string $email;
    #[Argument(name: 'password', description: 'User password')]
    public string $password;

    public function __invoke(WorkflowClientInterface $workflows): void
    {
        $wf = $workflows->newWorkflowStub(
            class: RegisterWorkflow::class,
            options: WorkflowOptions::new()->withTaskQueue('user.service'),
        );

        $result = $workflows->start($wf, new RegisterRequest([
            'email' => $this->email,
            'password' => $this->password,
            'name' => 'John Doe',
        ]))->getResult(User::class);
    }
}
