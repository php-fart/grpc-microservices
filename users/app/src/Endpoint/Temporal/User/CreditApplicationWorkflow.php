<?php

declare(strict_types=1);

namespace App\Endpoint\Temporal\User;

use Carbon\CarbonInterval;
use Internal\Shared\Temporal\OptionsTrait;
use Ramsey\Uuid\UuidInterface;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Internal\Workflow\ActivityProxy;
use Temporal\Workflow;
use Temporal\Workflow\WorkflowInterface;

#[AssignWorker('credit-application')]
#[WorkflowInterface]
final class CreditApplicationWorkflow
{
    use OptionsTrait;

    private ActivityProxy|CreditHistory $creditHistory;
    private ActivityProxy|IncomeVerification $incomeVerification;
    private ActivityProxy|Application $application;
    private ActivityProxy|Notifications $notifications;

    protected bool $approved = false;

    public function __construct()
    {
        $this->creditHistory = Workflow::newActivityStub(
            CreditHistory::class,
            $this->activityOptions('credit-application'),
        );

        $this->incomeVerification = Workflow::newActivityStub(
            IncomeVerification::class,
            $this->activityOptions('credit-application'),
        );

        $this->application = Workflow::newActivityStub(
            Application::class,
            $this->activityOptions('credit-application'),
        );

        $this->notifications = Workflow::newActivityStub(
            Notifications::class,
            $this->activityOptions('credit-application'),
        );
    }

    #[Workflow\WorkflowMethod]
    public function run(UuidInterface $applicationId)
    {
        // Шаг 1: Проверка кредитной истории
        $result = yield $this->creditHistory->check($applicationId);

        if (!$result->isEligible()) {
            yield $this->application->reject($applicationId);
            return;
        }

        // Шаг 2: Проверка доходов
        $result = yield $this->incomeVerification->check($applicationId);

        if (!$result->isEligible()) {
            yield $this->application->reject($applicationId);
            return;
        }

        yield $this->notifications->sendManagerNotification($applicationId);

        // Шаг 3: Одобрение заявки менеджером
        $isApproved = yield Workflow::awaitWithTimeout(
            CarbonInterval::hour(),
            fn() => $this->approved,
        );

        if ($isApproved) {
            yield $this->application->approve($applicationId);
            yield $this->notifications->sendApprovalNotification($applicationId);
        } else {
            yield $this->application->reject($applicationId);
            yield $this->notifications->sendRejectionNotification($applicationId);
        }
    }

    #[Workflow\SignalMethod]
    public function approve(): void
    {
        $this->approved = true;
    }
}
