<?php

declare(strict_types=1);

namespace App\Endpoint\Temporal;

use App\Domain\Subscription\Subscription;
use Carbon\CarbonInterval;
use Internal\Shared\Temporal\Activity\NotificationsActivity;
use Internal\Shared\Temporal\OptionsTrait;
use Ramsey\Uuid\UuidInterface;
use Temporal\Internal\Workflow\ActivityProxy;
use Temporal\Workflow;
use Internal\Shared\Temporal\TaskQueue;

final class SubscriptionWorkflow implements \Internal\Shared\Temporal\Workflow\SubscriptionWorkflow
{
    use OptionsTrait;

    protected bool $isPaid = false;

    private ActivityProxy|SubscriptionServiceActivity $subscriptions;
    private ActivityProxy|NotificationsActivity $notifications;

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

        $this->subscriptions = Workflow::newActivityStub(
            SubscriptionServiceActivity::class,
            $this->activityOptions(
                taskQueue: TaskQueue::Subscription,
                timeout: CarbonInterval::minute(),
                retryAttempts: 1,
            ),
        );
    }

    public function process(UuidInterface $userUuid, UuidInterface $subscriptionUuid)
    {
        // 1. Get subscription
        /** @var Subscription $subscription */
        $subscription = yield $this->subscriptions->getSubscription($subscriptionUuid);

        // 2. Subscribe
        yield $this->subscriptions->subscribe($userUuid);

        if ($subscription->trialDays > 0) {
            // 2. Start trial
            yield $this->subscriptions->startTrial($userUuid, $subscriptionUuid);
            yield $this->notifications->subscriptionStarted($userUuid);
            return;
        }

        // 3. Pay for subscription
        yield Workflow::await(fn() => $this->isPaid);
        yield $this->notifications->subscriptionStarted($userUuid);
    }

    public function updateStatus(bool $status): void
    {
        $this->isPaid = $status;
    }
}
