<?php

declare(strict_types=1);

namespace App\Endpoint\Temporal\User;

use Carbon\CarbonInterval;
use Internal\Shared\Temporal\Activity\NotificationsActivity;
use Internal\Shared\Temporal\OptionsTrait;
use Internal\Shared\Temporal\TaskQueue;
use Internal\Shared\Temporal\Workflow\EmailVerificationWorkflow;
use Internal\Shared\Temporal\Workflow\KYCWorkflow;
use Internal\Shared\Temporal\Workflow\RegisterUserWorkflow as RegisterUserWorkflowInterface;
use Internal\Shared\Temporal\Workflow\SubscriptionWorkflow as SubscriptionWorkflowInterface;
use Ramsey\Uuid\UuidInterface;
use Temporal\Internal\Workflow\ActivityProxy;
use Temporal\Internal\Workflow\ChildWorkflowProxy;
use Temporal\Promise;
use Temporal\Workflow;

final class RegisterUserWorkflow implements RegisterUserWorkflowInterface
{
    use OptionsTrait;

    private ActivityProxy|NotificationsActivity $notifications;
    private ChildWorkflowProxy|SubscriptionWorkflowInterface $subscription;
    private ChildWorkflowProxy|KYCWorkflow $kyc;
    private ChildWorkflowProxy|EmailVerificationWorkflow $emailVerification;

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

        $this->users = Workflow::newActivityStub(
            UserServiceActivity::class,
            $this->activityOptions(
                taskQueue: TaskQueue::UserRegistration,
                timeout: CarbonInterval::minute(),
                retryAttempts: 1,
            ),
        );
    }

    public function register(
        UuidInterface $uuid,
        UuidInterface $subscriptionUuid,
        string $verificationId
    ): void {
        yield $this->users->registerUser($uuid);

        $this->emailVerification = Workflow::newChildWorkflowStub(EmailVerificationWorkflow::class);
        $this->kyc = Workflow::newChildWorkflowStub(KYCWorkflow::class);
        $this->subscription = Workflow::newChildWorkflowStub(SubscriptionWorkflow::class);

        $promises = [
            $this->emailVerification->process($uuid),
            $this->kyc->process($uuid, $verificationId),
            $this->subscription->process($uuid, $subscriptionUuid)
        ];

        $reminders = [
            CarbonInterval::days(3),
            CarbonInterval::days(4),
            CarbonInterval::days(7),
        ];

        $isStepsCompleted = false;

        do {
            $reminder = \array_shift($reminders);

            $isStepsCompleted = yield Workflow::awaitWithTimeout(
                $reminder,
                Promise::all($promises),
            );

            if ($isStepsCompleted) {
                break;
            }

            yield $this->notifications->registrationReminder($uuid);
        } while (!empty($reminders));

        if (!$isStepsCompleted) {
            // Remove user from DB
            // Send notification to user about failed registration
            yield $this->users->removeUser($uuid);
            yield $this->notifications->accountDeleted($uuid);

            return;
        }

        yield $this->notifications->sendWelcomeEmail($uuid);
    }

    public function emailVerified()
    {
        $this->emailVerification->updateStatus(true);
    }

    public function kycVerified(bool $status)
    {
        $this->kyc->updateStatus($status);
    }

    public function subscriptionPaid(string $transactionId)
    {
        yield $this->subscription->updateStatus(true);
    }

    public function validateSubscriptionTransaction(string $transactionId): void
    {

    }
}
