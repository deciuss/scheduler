<?php


namespace app\Handler;


use App\DBAL\PlanStatus;
use app\Message\ScheduleCalculationMessage;
use app\Message\Message;
use App\Repository\PlanRepository;


class ScheduleCalculationHandler extends ChainedHandler
{

    private PlanRepository $planRepository;

    protected function canHandle(Message $message): bool
    {
        return in_array(
            $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
            [
                PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED
            ]
        );
    }

    public function __construct(
        NormalizedDataGenerationHandler $dataGenerationHandler,
        PlanRepository $planRepository
    ) {
        $this->setNext($dataGenerationHandler);
        $this->planRepository = $planRepository;
    }

    public function __invoke(Message $message)
    {
        assert($message instanceof ScheduleCalculationMessage, "Invalid message type.");
        if (! $this->canHandle($message)) {
            return $this->invokeNext($message);
        }



    }
}