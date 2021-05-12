<?php


namespace App\ScheduleCalculator\Handler\CalculateScheduleChain;


use App\DBAL\PlanStatus;
use App\ChainHandler\ChainHandler;
use App\ScheduleCalculator\Message\CalculateSchedule;
use App\Message\Message;
use App\Repository\PlanRepository;
use Psr\Log\LoggerInterface;


class NormalisationErrorHandler extends ChainHandler
{

    private PlanRepository $planRepository;

    protected function canHandle(Message $message): bool
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
    protected function handle(Message $message) : void
    {
        throw new \RuntimeException(sprintf("Normalisation for plan %d failed.", $message->getPlanId()));
    }
}