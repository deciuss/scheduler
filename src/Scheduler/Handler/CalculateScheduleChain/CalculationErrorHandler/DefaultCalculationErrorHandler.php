<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain\CalculationErrorHandler;

use App\DBAL\PlanStatus;
use App\Scheduler\Handler\ChainHandlerAbstract;
use App\Scheduler\Message\CalculateSchedule;
use App\Scheduler\Message;
use App\Repository\PlanRepository;
use App\StateMachine\Entity\Plan\PlanStatusStateMachine;
use Psr\Log\LoggerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\CalculationErrorHandler as CalculationErrorHandlerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\NormalisationErrorHandler as NormalisationErrorHandlerInterface;

class DefaultCalculationErrorHandler extends ChainHandlerAbstract implements CalculationErrorHandlerInterface
{

    public function __construct(
        private PlanStatusStateMachine $planStatusStateMachine,
        NormalisationErrorHandlerInterface $normalisationErrorHandler,
        LoggerInterface $logger

    ) {
        parent::__construct($normalisationErrorHandler, $logger);
    }

    public function canHandle(Message $message): bool
    {
        if (! $message instanceof CalculateSchedule) {
            return false;
        }

        return $this->planStatusStateMachine->is($message->getPlanId(), PlanStatus::PLAN_STATUS_CALCULATION_ERROR);
    }

    /**
     * @param Message $message
     * @todo
     */
    public function handle(Message $message) : void
    {
        throw new \RuntimeException(sprintf("Calculation for plan %d failed.", $message->getPlanId()));
    }
}