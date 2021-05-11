<?php


namespace app\Handler;


use App\DBAL\PlanStatus;
use app\Message\ScheduleCalculationMessage;
use app\Message\Message;
use App\Repository\PlanRepository;


class EventFillingHandler extends ChainedHandler
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

    public function __construct(CalculationErrorHandler $calculationErrorHandler)
    {
        $this->setNext($calculationErrorHandler);
    }

    public function __invoke(Message $message)
    {
        assert($message instanceof ScheduleCalculationMessage, "Invalid message type.");
        if (! $this->canHandle($message)) {
            return $this->invokeNext($message);
        }

    }
}