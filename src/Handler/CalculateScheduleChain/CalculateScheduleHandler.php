<?php


namespace App\Handler\CalculateScheduleChain;


use App\DBAL\PlanStatus;
use App\Handler\ChainedHandler;
use App\Message\CalculateSchedule;
use App\Message\Message;
use App\Repository\PlanRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


class CalculateScheduleHandler extends ChainedHandler implements MessageHandlerInterface
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
        NormalizedDataGenerationHandler $dataGenerationHandler,
        PlanRepository $planRepository
    ) {
        $this->setNextHandler($dataGenerationHandler);
        $this->planRepository = $planRepository;
    }

    public function __invoke(CalculateSchedule $message)
    {
        if (! $this->canHandle($message)) {
            return $this->nextHandler($message);
        }



    }
}