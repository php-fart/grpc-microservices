<?php

declare(strict_types=1);

namespace App\Endpoint\Temporal\User;

use App\Domain\User\ValueObject\Uuid;
use Carbon\CarbonInterval;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Workflow;
use Temporal\Workflow\WorkflowInterface;

#[AssignWorker('user.service')]
#[WorkflowInterface]
final class SubscriptionWorkflow
{
    public bool $trial = true;

    #[Workflow\QueryMethod]
    public function isTrial(): bool
    {
        return $this->trial;
    }

    public function subscribe(Uuid $userUuid, Uuid $subscriptionUuid)
    {
        $user = yield $this->users->getUser($userUuid);

        $subscription = yield $this->subscriptions->getSubscription($subscriptionUuid);
        // 1. cost of subscription
        // 2. user balance
        // 3. Period of subscription

        $this->trial = $user->hasTrial();
        $period = CarbonInterval::month();
        $cost = $subscription->cost;

        $maxIterations = 10;

        while (true) {
            yield Workflow::timer($period);
            if ($this->trial) {
                $this->trial = false;
                yield $this->users->finishTrial($userUuid);
                yield $this->notifications->sendNotification(
                    $user->uuid,
                    'Your trial period has ended',
                );

                continue;
            }

            $balance = yield $this->payments->getBalance($userUuid);

            if ($balance < $cost) {
                yield $this->notifications->sendNotification(
                    $user->uuid,
                    'Your balance is not enough to pay for the subscription',
                );

                break;
            }

            $saga = new Workflow\Saga();
            $saga->setParallelCompensation(true);

            try {
                yield $this->payments->pay($userUuid, $subscriptionUuid, $cost);
                yield $this->notifications->sendNotification(
                    $user->uuid,
                    'You have successfully paid for the subscription',
                );
                $saga->addCompensation(function () {
                    yield $this->subscriptions->cancelSubscription($subscriptionUuid);
                });
            } catch (\Throwable $e) {
                yield $saga->compensate();
                yield $this->notifications->sendNotification(
                    $user->uuid,
                    'An error occurred while paying for the subscription',
                );
            }
        }
    }
}
