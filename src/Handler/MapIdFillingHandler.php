<?php


namespace app\Handler;


use App\DBAL\PlanStatus;
use app\Message\Message;
use app\Message\ScheduleCalculationMessage;
use App\Repository\PlanRepository;


class MapIdFillingHandler extends ChainedHandler
{

    private PlanRepository $planRepository;

    protected function canHandle(Message $message): bool
    {
        return in_array(
            $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
            [
                PlanStatus::PLAN_STATUS_EVENT_FILLING_FINISHED
            ]
        );
    }

    public function __construct(
        EventFillingHandler $eventFillingHandler,
        PlanRepository $planRepository
    ) {
        $this->setNext($eventFillingHandler);
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