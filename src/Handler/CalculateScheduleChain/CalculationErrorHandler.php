<?php


namespace App\Handler\CalculateScheduleChain;


use App\DBAL\PlanStatus;
use App\Handler\ChainedHandler;
use App\Message\CalculateSchedule;
use App\Message\Message;
use App\Repository\PlanRepository;
use Psr\Log\LoggerInterface;


class CalculationErrorHandler extends ChainedHandler
{

    private PlanRepository $planRepository;

    protected function canHandle(Message $message): bool
    {
        return in_array(
            $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
            [
                PlanStatus::PLAN_STATUS_UNDER_CONSTRUCTION
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

    public function __invoke(CalculateSchedule $message) : void
    {
        if (! $this->canHandle($message)) {
            $this->invokeNextHandler($message);
            return;
        }

        $this->logger->info(sprintf('%s started handling message: %s %s', get_class($this), get_class($message), json_encode($message)));
        throw new \RuntimeException(sprintf("Calculation for plan %d failed.", $message->getPlanId()));
        $this->logger->info(sprintf('%s finished handling message: %s %s', get_class($this), get_class($message), json_encode($message)));
    }
}