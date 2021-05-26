<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain\EventFillingHandler;

use App\Scheduler\Handler\ChainHandlerAbstract;
use App\Scheduler\Message;
use App\Scheduler\Message\CalculateSchedule;
use App\Scheduler\Normalization\EventFiller;
use App\StateMachine\Entity\Plan\PlanStatusStateMachine;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Scheduler\Handler\CalculateScheduleChain\EventFillingHandler as EventFillingHandlerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\LockHandler as LockHandlerInterface;

class DefaultEventFillingHandler extends ChainHandlerAbstract implements EventFillingHandlerInterface
{

    public function __construct(
        private PlanStatusStateMachine $planStatusStateMachine,
        private MessageBusInterface $messageBus,
        private EventFiller $eventFiller,
        LockHandlerInterface $lockHandler,
        LoggerInterface $logger,
    ) {
        parent::__construct($lockHandler, $logger);
    }

    public function canHandle(Message $message): bool
    {
        if (! $message instanceof CalculateSchedule) {
            return false;
        }

        return $this->planStatusStateMachine->can($message->getPlanId(),'event_filling_starting');
    }

    public function handle(Message $message) : void
    {
        $this->planStatusStateMachine->apply($message->getPlanId(), 'event_filling_starting');
        ($this->eventFiller)($message->getPlanId());
        $this->planStatusStateMachine->apply($message->getPlanId(), 'event_filling_finishing');
        $this->messageBus->dispatch($message);
    }
}