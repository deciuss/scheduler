<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain;

use App\DBAL\PlanStatus;
use App\ChainHandler\ChainHandler;
use App\Scheduler\Message\CalculateSchedule;
use App\Message\Message;
use App\Repository\PlanRepository;
use Psr\Log\LoggerInterface;


class CalculationErrorHandler extends ChainHandler
{

    private PlanRepository $planRepository;

    protected function canHandle(Message $message): bool
    {
        return
            $message instanceof CalculateSchedule
            && in_array(
                $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
                [
                    PlanStatus::PLAN_STATUS_CALCULATION_ERROR
                ]
            );
    }

    public function __construct(
        NormalisationErrorHandler $normalisationErrorHandler,
        LoggerInterface $logger,
        PlanRepository $planRepository
    ) {
        parent::__construct($normalisationErrorHandler, $logger);
        $this->planRepository = $planRepository;
    }

    /**
     * @param Message $message
     * @todo
     */
    protected function handle(Message $message) : void
    {
        throw new \RuntimeException(sprintf("Calculation for plan %d failed.", $message->getPlanId()));
    }
}