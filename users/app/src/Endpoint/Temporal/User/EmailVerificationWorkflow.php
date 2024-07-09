<?php

declare(strict_types=1);

namespace App\Endpoint\Temporal\User;

use Carbon\CarbonInterval;
use Internal\Shared\Temporal\Activity\NotificationsActivity;
use Internal\Shared\Temporal\OptionsTrait;
use Temporal\Internal\Workflow\ActivityProxy;
use Internal\Shared\Temporal\TaskQueue;
use Temporal\Workflow;

final class EmailVerificationWorkflow implements \Internal\Shared\Temporal\Workflow\EmailVerificationWorkflow
{
    use OptionsTrait;

    private ActivityProxy|NotificationsActivity $notifications;

    protected bool $isVerified = false;

    public function __construct()
    {
        $this->notifications = Workflow::newActivityStub(
            NotificationsActivity::class,
            $this->activityOptions(
                taskQueue: TaskQueue::Notifications,
                timeout: CarbonInterval::minute(),
                retryAttempts: 3,
            ),
        );
    }

    public function updateStatus(bool $status): void
    {
        $this->isVerified = $status;
    }

    public function currentStatus(): bool
    {
        return $this->isVerified;
    }

    public function process(UuidInterface $userUuid)
    {
        // 1. Send email verification link
        yield $this->notifications->sendVerificationLink($userUuid);

        // 2. Wait for verification
        yield Workflow::await(fn() => $this->isVerified);

        // 3. Send welcome email
        yield $this->notifications->emailVerified($userUuid);
    }
}
