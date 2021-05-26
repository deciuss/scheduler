<?php

declare(strict_types=1);

namespace App\Scheduler\Handler;

use App\Scheduler\CalculatorExecutor;
use App\Scheduler\Message\CalculateSchedule;
use App\Scheduler\Message;
use App\StateMachine\Entity\Plan\PlanStatusStateMachine;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler as NormalizedDataGenerationHandlerInterface;

class CalculateScheduleHandler extends ChainHandlerAbstract implements MessageHandlerInterface
{

    public function canHandle(Message $message): bool
    {
        return $this->planStatusStateMachine->can($message->getPlanId(), 'schedule_calculation_starting');
    }

    public function __construct(
        private PlanStatusStateMachine $planStatusStateMachine,
        private CalculatorExecutor $calculatorExecutor,
        NormalizedDataGenerationHandlerInterface $normalizedDataGenerationHandler,
        LoggerInterface $logger
    ) {
        parent::__construct($normalizedDataGenerationHandler, $logger);
    }

    public function handle(Message $message) : void
    {
        $this->planStatusStateMachine->apply($message->getPlanId(), 'schedule_calculation_starting');

        try {
            ($this->calculatorExecutor)((string) $message->getPlanId());
            $this->planStatusStateMachine->apply($message->getPlanId(), 'schedule_calculation_finishing');
        } catch (\Exception $e) {
            $this->planStatusStateMachine->apply($message->getPlanId(), 'calculation_error_rising');
            throw $e;
        }
    }

    public function __invoke(CalculateSchedule $message)
    {
        $this->executeHandler($message);
    }
}