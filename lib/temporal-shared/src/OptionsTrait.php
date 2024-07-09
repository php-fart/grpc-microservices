<?php

declare(strict_types=1);

namespace Internal\Shared\Temporal;

use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\Workflow\ChildWorkflowOptions;

trait OptionsTrait
{
    protected const TIMEOUT = 60;
    protected const RETRY_ATTEMPTS = 10;

    /**
     * @param string $taskQueue used task queue from TaskQueue
     *
     * @return ActivityOptions
     */
    private function activityOptions(
        string $taskQueue,
        int|\DateInterval|null $timeout = null,
        ?int $retryAttempts = null,
    ): ActivityOptions {
        return ActivityOptions::new()
            ->withTaskQueue($taskQueue)
            ->withStartToCloseTimeout($timeout ?? self::TIMEOUT)
            ->withRetryOptions(
                RetryOptions::new()
                    ->withMaximumAttempts($retryAttempts ?? self::RETRY_ATTEMPTS)
                    ->withBackoffCoefficient(2.5),
            );
    }

    /**
     * Must be used for each workflow activity
     *
     * @param string $taskQueue used task queue from TaskQueue
     *
     * @return ChildWorkflowOptions
     */
    private function workflowOptions(
        string $taskQueue,
    ): ChildWorkflowOptions {
        return ChildWorkflowOptions::new()->withTaskQueue($taskQueue);
    }
}
