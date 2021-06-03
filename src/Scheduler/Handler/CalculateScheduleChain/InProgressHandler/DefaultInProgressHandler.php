<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain\InProgressHandler;

use App\DBAL\PlanStatus;
use App\Scheduler\Handler\ChainHandlerAbstract;
use App\Scheduler\Message;
use App\Scheduler\Message\CalculateSchedule;
use App\StateMachine\Entity\Plan\PlanStatusStateMachine;
use Psr\Log\LoggerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\InProgressHandler as InProgressHandlerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\CalculationErrorHandler as CalculationErrorHandlerInterface;

class DefaultInProgressHandler extends ChainHandlerAbstract implements InProgressHandlerInterface
{

    public function __construct(
        private PlanStatusStateMachine $planStatusStateMachine,
        CalculationErrorHandlerInterface $calculationErrorHandler,
        LoggerInterface $logger
    ) {
        parent::__construct($calculationErrorHandler, $logger);
    }

    public function canHandle(Message $message): bool
    {
        if (! $message instanceof CalculateSchedule) {
            return false;
        }

        return
            $this->planStatusStateMachine->is(
                $message->getPlanId(), PlanStatus::PLAN_STATUS_EVENT_FILLING_STARTED
            )
            || $this->planStatusStateMachine->is(
                $message->getPlanId(), PlanStatus::PLAN_STATUS_MAP_ID_FILLING_STARTED
            )
            || $this->planStatusStateMachine->is(
                $message->getPlanId(), PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_STARTED
            )
            || $this->planStatusStateMachine->is(
                $message->getPlanId(), PlanStatus::PLAN_STATUS_CALCULATION_STARTED
            );
    }

    /**
     * @param Message $message
     * @todo
     */
    public function handle(Message $message) : void
    {
        // Work in progress, do nothing
    }
}