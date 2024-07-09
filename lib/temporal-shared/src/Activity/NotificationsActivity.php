<?php

declare(strict_types=1);

namespace Internal\Shared\Temporal\Activity;

use Internal\Shared\Temporal\TaskQueue;
use Ramsey\Uuid\UuidInterface;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[AssignWorker(taskQueue: TaskQueue::Notifications)]
#[ActivityInterface(prefix: 'notifications.')]
interface NotificationsActivity
{
    // Here we can use {@link Activity::doNotCompleteOnReturn()}
    // See https://github.com/temporalio/samples-php/blob/master/app/src/AsyncActivityCompletion/GreetingActivity.php

    #[ActivityMethod]
    public function sendVerificationLink(UuidInterface $userUuid): void;

    #[ActivityMethod]
    public function emailVerified(UuidInterface $userUuid): void;

    #[ActivityMethod]
    public function kycPending(UuidInterface $userUuid): void;

    #[ActivityMethod]
    public function kycApproved(UuidInterface $userUuid): void;

    #[ActivityMethod]
    public function kycRejected(UuidInterface $userUuid): void;

    #[ActivityMethod]
    public function paymentSuccess(UuidInterface $userUuid): void;

    #[ActivityMethod]
    public function paymentFailed(UuidInterface $userUuid): void;

    #[ActivityMethod]
    public function subscriptionStarted(UuidInterface $userUuid): void;

    #[ActivityMethod]
    public function subscriptionEnded(UuidInterface $userUuid): void;

    #[ActivityMethod]
    public function registrationReminder(UuidInterface $userUuid): void;

    #[ActivityMethod]
    public function accountDeleted(UuidInterface $userUuid): void;

    #[ActivityMethod]
    public function sendWelcomeEmail(UuidInterface $userUuid): void;
}