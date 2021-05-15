<?php

declare(strict_types=1);

namespace App\Scheduler\Infrastructure\Handler\CalculateScheduleChain;

use App\DBAL\PlanStatus;
use App\ChainHandler\ChainHandlerAbstract;
use App\Scheduler\Message\CalculateSchedule;
use App\Message\Message;
use App\Repository\PlanRepository;
use Psr\Log\LoggerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\CalculationErrorHandler as CalculationErrorHandlerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\NormalisationErrorHandler as NormalisationErrorHandlerInterface;

class CalculationErrorHandler extends ChainHandlerAbstract implements CalculationErrorHandlerInterface
{

    private PlanRepository $planRepository;

    public function canHandle(Message $message): bool
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
        NormalisationErrorHandlerInterface $normalisationErrorHandler,
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
    public function handle(Message $message) : void
    {
        throw new \RuntimeException(sprintf("Calculation for plan %d failed.", $message->getPlanId()));
    }
}