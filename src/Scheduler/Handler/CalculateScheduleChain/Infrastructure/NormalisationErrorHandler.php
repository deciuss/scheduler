<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain\Infrastructure;

use App\DBAL\PlanStatus;
use App\ChainHandler\ChainHandlerAbstract;
use App\Scheduler\Message\CalculateSchedule;
use App\Message\Message;
use App\Repository\PlanRepository;
use Psr\Log\LoggerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\NormalisationErrorHandler as NormalisationErrorHandlerInterface;

class NormalisationErrorHandler extends ChainHandlerAbstract implements NormalisationErrorHandlerInterface
{
    private PlanRepository $planRepository;

    public function canHandle(Message $message): bool
    {
        return
            $message instanceof CalculateSchedule
            && in_array(
                $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
                [
                    PlanStatus::PLAN_STATUS_NORMALISATION_ERROR
                ]
            );
    }

    public function __construct(
        PlanRepository $planRepository,
        LoggerInterface $logger
    ) {
        parent::__construct(null, $logger);
        $this->planRepository = $planRepository;
    }

    /**
     * @param Message $message
     * @todo
     */
    public function handle(Message $message) : void
    {
        throw new \RuntimeException(sprintf("Normalisation for plan %d failed.", $message->getPlanId()));
    }
}