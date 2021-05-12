<?php


namespace App\Handler\CalculateScheduleChain;


use App\DBAL\PlanStatus;
use App\Handler\ChainedHandler;
use App\Message\CalculateSchedule;
use App\Message\Message;
use App\Repository\PlanRepository;
use Psr\Log\LoggerInterface;


class InProgressHandler extends ChainedHandler
{

    private PlanRepository $planRepository;

    protected function canHandle(Message $message): bool
    {
        return in_array(
            $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
            [
                PlanStatus::PLAN_STATUS_EVENT_FILLING_STARTED,
                PlanStatus::PLAN_STATUS_MAP_ID_FILLING_STARTED,
                PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_STARTED,
                PlanStatus::PLAN_STATUS_SCHEDULE_CALCULATION_STARTED
            ]
        );
    }

    public function __construct(
        CalculationErrorHandler $calculationErrorHandler,
        LoggerInterface $logger,
        PlanRepository $planRepository
    ) {
        parent::__construct($calculationErrorHandler, $logger);
        $this->planRepository = $planRepository;
    }

    public function __invoke(CalculateSchedule $message) : void
    {
        if (! $this->canHandle($message)) {
            $this->invokeNextHandler($message);
            return;
        }

        $this->logger->info(sprintf('%s started handling message: %s %s', get_class($this), get_class($message), json_encode($message)));
        // Work in progress, do nothing
        $this->logger->info(sprintf('%s finished handling message: %s %s', get_class($this), get_class($message), json_encode($message)));
    }
}