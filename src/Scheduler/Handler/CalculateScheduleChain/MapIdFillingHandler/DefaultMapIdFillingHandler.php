<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain\MapIdFillingHandler;

use App\Scheduler\Handler\ChainHandlerAbstract;
use App\Scheduler\Message;
use App\Scheduler\Message\CalculateSchedule;
use App\Scheduler\Normalization\MapIdFiller;
use App\StateMachine\Entity\Plan\PlanStatusStateMachine;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Scheduler\Handler\CalculateScheduleChain\MapIdFillingHandler as MapIdFillingHandlerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\EventFillingHandler as EventFillingHandlerInterface;

class DefaultMapIdFillingHandler extends ChainHandlerAbstract implements MapIdFillingHandlerInterface
{

    /**
     * @var MapIdFiller[]
     */
    private array $mapIdFillers;

    public function __construct(
        private PlanStatusStateMachine $planStatusStateMachine,
        private MessageBusInterface $messageBus,
        MapIdFiller\EventFiller $eventFiller,
        MapIdFiller\RoomFiller $roomFiller,
        MapIdFiller\StudentGroupFiller $studentGroupFiller,
        MapIdFiller\TeacherFiller $teacherFiller,
        MapIdFiller\TimeslotFiller $timeslotFiller,
        EventFillingHandlerInterface $eventFillingHandler,
        LoggerInterface $logger
    ) {
        parent::__construct($eventFillingHandler, $logger);
        $this->mapIdFillers = [
            $eventFiller,
            $roomFiller,
            $studentGroupFiller,
            $teacherFiller,
            $timeslotFiller
        ];
    }

    public function canHandle(Message $message): bool
    {
        if (! $message instanceof CalculateSchedule) {
            return false;
        }

        return $this->planStatusStateMachine->can($message->getPlanId(),'map_id_filling_starting');
    }

    public function handle(Message $message) : void
    {
        $this->planStatusStateMachine->apply($message->getPlanId(),'map_id_filling_starting');
        foreach ($this->mapIdFillers as $mapIdFiller) {
            $mapIdFiller($message->getPlanId());
        }
        $this->planStatusStateMachine->apply($message->getPlanId(),'map_id_filling_finishing');
        $this->messageBus->dispatch($message);
    }
}
