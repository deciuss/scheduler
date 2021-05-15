<?php

declare(strict_types=1);

namespace App\Scheduler\Infrastructure\Handler\CalculateScheduleChain;

use App\DBAL\PlanStatus;
use App\ChainHandler\ChainHandlerAbstract;
use App\Scheduler\Message\CalculateSchedule;
use App\Message\Message;
use App\Repository\PlanRepository;
use Psr\Log\LoggerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\InProgressHandler as InProgressHandlerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\CalculationErrorHandler as CalculationErrorHandlerInterface;

class InProgressHandler extends ChainHandlerAbstract implements InProgressHandlerInterface
{
    private PlanRepository $planRepository;

    public function __construct(
        CalculationErrorHandlerInterface $calculationErrorHandler,
        LoggerInterface $logger,
        PlanRepository $planRepository
    ) {
        parent::__construct($calculationErrorHandler, $logger);
        $this->planRepository = $planRepository;
    }

    public function canHandle(Message $message): bool
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
    public function handle(Message $message) : void
    {
        // Work in progress, do nothing
    }
}