<?php

declare(strict_types=1);

namespace App\Endpoint\Temporal\User;

use Carbon\CarbonInterval;
use Internal\Shared\Temporal\OptionsTrait;
use Internal\Shared\Temporal\Workflow\ExceptionHelper;
use Ramsey\Uuid\UuidInterface;
use Temporal\Exception\Failure\ActivityFailure;
use Temporal\Internal\Workflow\ActivityProxy;
use Temporal\Workflow;
use Internal\Shared\Temporal\TaskQueue;

final class KYCWorkflow implements \Internal\Shared\Temporal\Workflow\KYCWorkflow
{
    use OptionsTrait;

    protected bool $isVerified = false;

    private ActivityProxy|KycServiceActivity $kycService;

    public function __construct()
    {
        $this->kycService = Workflow::newActivityStub(
            KycServiceActivity::class,
            $this->activityOptions(
                taskQueue: TaskQueue::Kyc,
                timeout: CarbonInterval::minute(),
                retryAttempts: 3,
            ),
        );
    }

    public function process(UuidInterface $userUuid, string $kycVerificationId)
    {
        $lastStatus = null;

        while (true) {
            try {
                $status = yield $this->kycService->verify($userUuid, $kycVerificationId);
            } catch (ActivityFailure $e) {
                if (ExceptionHelper::isThrown($e, RateLimitExceededException::class)) {
                    yield Workflow::await(CarbonInterval::minutes(10));
                }
            }

            if ($lastStatus !== $status) {
                yield $this->notifications->kycStatusUpdated($userUuid, $status);

                $lastStatus = $status;

                yield $this->kycHistory->save($userUuid, $status);
            }

            $isVerified = yield Workflow::awaitWithTimeout(
                CarbonInterval::minutes(5),
                fn() => $this->isVerified,
            );

            if ($isVerified) {
                break;
            }
        }

        yield $this->notifications->kycVerified($userUuid);
    }

    public function updateStatus(bool $status): void
    {
        $this->isVerified = $status;
    }

    public function currentStatus(): bool
    {
        return $this->isVerified;
    }
}
