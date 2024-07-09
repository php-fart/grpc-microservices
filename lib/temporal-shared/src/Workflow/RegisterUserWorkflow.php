<?php

declare(strict_types=1);

namespace Internal\Shared\Temporal\Workflow;

use Internal\Shared\Temporal\TaskQueue;
use Ramsey\Uuid\UuidInterface;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Support\VirtualPromise;
use Temporal\Workflow\UpdateMethod;
use Temporal\Workflow\UpdateValidatorMethod;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[AssignWorker(taskQueue: TaskQueue::UserRegistration)]
#[WorkflowInterface]
interface RegisterUserWorkflow
{
    #[WorkflowMethod]
    public function register(
        UuidInterface $uuid,
        UuidInterface $subscriptionUuid,
        string $verificationId
    ): void;

    /**
     * @return VirtualPromise<void>
     */
    #[UpdateMethod(name: 'emailVerified')]
    public function emailVerified();

    /**
     * @return VirtualPromise<void>
     */
    #[UpdateMethod(name: 'kycVerified')]
    public function kycVerified(bool $status);

    /**
     * @return VirtualPromise<void>
     */
    #[UpdateMethod(name: 'subscriptionPaid')]
    public function subscriptionPaid(string $transactionId);

    #[UpdateValidatorMethod(forUpdate: 'subscriptionPaid')]
    public function validateSubscriptionTransaction(string $transactionId): void;
}