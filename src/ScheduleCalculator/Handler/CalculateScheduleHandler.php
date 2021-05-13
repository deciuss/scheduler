<?php

declare(strict_types=1);

namespace App\ScheduleCalculator\Handler;

use App\DBAL\PlanStatus;
use App\ChainHandler\ChainHandler;
use App\ScheduleCalculator\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler;
use App\ScheduleCalculator\Message\CalculateSchedule;
use App\Message\Message;
use App\Repository\PlanRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


class CalculateScheduleHandler extends ChainHandler implements MessageHandlerInterface
{

    private PlanRepository $planRepository;

    protected function canHandle(Message $message): bool
    {
        return in_array(
            $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
            [
                PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED,
                PlanStatus::PLAN_STATUS_SCHEDULE_CALCULATION_FINISHED
            ]
        );
    }

    public function __construct(
        NormalizedDataGenerationHandler $normalizedDataGenerationHandler,
        LoggerInterface $logger,
        PlanRepository $planRepository
    ) {
        parent::__construct($normalizedDataGenerationHandler, $logger);
        $this->planRepository = $planRepository;
    }

    protected function handle(Message $message) : void
    {
        // @todo
    }

    public function __invoke(CalculateSchedule $message)
    {
        $this->executeHandler($message);
    }
}