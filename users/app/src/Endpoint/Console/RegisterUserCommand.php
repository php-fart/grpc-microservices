<?php

declare(strict_types=1);

namespace App\Endpoint\Console;

use App\Domain\User\ValueObject\Uuid;
use App\Endpoint\Temporal\User\RegisterWorkflow;
use GRPC\Services\Auth\v1\RegisterRequest;
use GRPC\Services\Users\v1\User;
use Internal\Shared\Temporal\TaskQueue;
use Internal\Shared\Temporal\Workflow\ExceptionHelper;
use Internal\Shared\Temporal\Workflow\RegisterUserWorkflow;
use Spiral\Console\Attribute\Argument;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Command;
use Temporal\Client\WorkflowClientInterface;
use Temporal\Client\WorkflowOptions;
use Temporal\Common\IdReusePolicy;

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
        $userUuid = Uuid::generate();

        $wf = $workflows->newWorkflowStub(
            class: RegisterUserWorkflow::class, // user.register.workflow
            options: WorkflowOptions::new()
                ->withTaskQueue(TaskQueue::UserRegistration)
                ->withWorkflowId((string) $userUuid)
                ->withWorkflowIdReusePolicy(IdReusePolicy::RejectDuplicate),
        );

//        try {
            $result = $workflows->start($wf, new RegisterRequest([
                'email' => $this->email,
                'password' => $this->password,
                'name' => 'John Doe',
            ]))->getResult(User::class);
//        } catch (\Throwable $e) {
//            dump(ExceptionHelper::convertToRealException($e));
//            return;
//        }

        $runWf = $workflows->newRunningWorkflowStub(RegisterUserWorkflow::class, (string) $userUuid);
        $runWf->subscriptionPaid('transaction-id-1234');
    }
}
