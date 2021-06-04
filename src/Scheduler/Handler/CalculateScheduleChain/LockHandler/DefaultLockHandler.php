<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain\LockHandler;

use App\Scheduler\Handler\CalculateScheduleChain\InProgressHandler as InProgressHandlerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\LockHandler as LockHandlerInterface;
use App\Scheduler\Handler\ChainHandlerAbstract;
use App\Scheduler\Message;
use App\Scheduler\Message\CalculateSchedule;
use App\StateMachine\Entity\Plan\PlanStatusStateMachine;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DefaultLockHandler extends ChainHandlerAbstract implements LockHandlerInterface
{
    public function __construct(
        private PlanStatusStateMachine $planStatusStateMachine,
        private MessageBusInterface $messageBus,
        InProgressHandlerInterface $inProgressHandler,
        LoggerInterface $logger
    ) {
        parent::__construct($inProgressHandler, $logger);
    }

    public function canHandle(Message $message): bool
    {
        if (!$message instanceof CalculateSchedule) {
            return false;
        }

        return $this->planStatusStateMachine->can($message->getPlanId(), 'locking');
    }

    public function handle(Message $message): void
    {
        $this->planStatusStateMachine->apply($message->getPlanId(), 'locking');
        $this->messageBus->dispatch($message);
    }
}
