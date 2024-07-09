<?php

declare(strict_types=1);

namespace Internal\Shared\Temporal\Workflow;

use Internal\Shared\Temporal\TaskQueue;
use Ramsey\Uuid\UuidInterface;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Workflow\QueryMethod;
use Temporal\Workflow\SignalMethod;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[AssignWorker(taskQueue: TaskQueue::EmailVerification)]
#[WorkflowInterface]
interface EmailVerificationWorkflow
{
    #[WorkflowMethod]
    public function process(UuidInterface $userUuid);

    #[SignalMethod]
    public function updateStatus(bool $status): void;

    #[QueryMethod]
    public function currentStatus(): bool;
}