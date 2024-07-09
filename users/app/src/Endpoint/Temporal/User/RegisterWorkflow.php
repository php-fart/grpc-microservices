<?php

declare(strict_types=1);

namespace App\Endpoint\Temporal\User;

use Carbon\CarbonInterval;
use GRPC\Services\Auth\v1\RegisterRequest;
use GRPC\Services\Users\v1\User;
use Internal\Shared\Temporal\Activity\NotificationsActivity;
use Internal\Shared\Temporal\OptionsTrait;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Internal\Workflow\ActivityProxy;
use Temporal\Workflow;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[AssignWorker('user.service')]
#[WorkflowInterface]
final class RegisterWorkflow
{
    use OptionsTrait;

    private ActivityProxy|UserServiceActivity $users;
    private ActivityProxy|NotificationsActivity $notifications;

    public function __construct()
    {
        $this->users = Workflow::newActivityStub(
            UserServiceActivity::class,
            $this->activityOptions(
                taskQueue: 'user.service',
                timeout: CarbonInterval::minute(),
                retryAttempts: 1,
            ),
        );

        $this->notifications = Workflow::newActivityStub(
            NotificationsActivity::class,
            $this->activityOptions(
                taskQueue: 'notifications.service',
                timeout: CarbonInterval::minutes(5),
                retryAttempts: 3,
            ),
        );
    }

    #[WorkflowMethod(name: 'RegisterUser')]
    public function register(RegisterRequest $request)
    {
        // 1. Create user in the database
        /** @var User $user */
        // user.service.register
        $user = yield $this->users->register($request);

        // 2. Create auth token
        // $token = yield $this->auth->createToken($user->uuid);

        // 3. Create user notification settings
        // yield $this->notifications->createSettings($user);

        // 2. Send email to the user
        // yield $this->notifications->sendWelcomeEmail($user);

        return $user;
    }
}
