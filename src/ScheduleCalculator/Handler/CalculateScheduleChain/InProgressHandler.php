<?php

declare(strict_types=1);

namespace App\ScheduleCalculator\Handler\CalculateScheduleChain;

use App\DBAL\PlanStatus;
use App\ChainHandler\ChainHandler;
use App\ScheduleCalculator\Message\CalculateSchedule;
use App\Message\Message;
use App\Repository\PlanRepository;
use Psr\Log\LoggerInterface;


class InProgressHandler extends ChainHandler
{
    private PlanRepository $planRepository;

    public function __construct(
        CalculationErrorHandler $calculationErrorHandler,
        LoggerInterface $logger,
        PlanRepository $planRepository
    ) {
        parent::__construct($calculationErrorHandler, $logger);
        $this->planRepository = $planRepository;
    }

    protected function canHandle(Message $message): bool
    {
        return
            $message instanceof CalculateSchedule
            && in_array(
                $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
                [
                    PlanStatus::PLAN_STATUS_EVENT_FILLING_STARTED,
                    PlanStatus::PLAN_STATUS_MAP_ID_FILLING_STARTED,
                    PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_STARTED,
                    PlanStatus::PLAN_STATUS_SCHEDULE_CALCULATION_STARTED
                ]
            );
    }

    /**
     * @param Message $message
     * @todo
     */
    protected function handle(Message $message) : void
    {
        // Work in progress, do nothing
    }
}