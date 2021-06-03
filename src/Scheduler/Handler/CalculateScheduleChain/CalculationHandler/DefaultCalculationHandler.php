<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain\CalculationHandler;

use App\Scheduler\Count\CountExecutor;
use App\Scheduler\Exception\FeasibleSolutionNotFoundException;
use App\Scheduler\Handler\CalculateScheduleChain\CalculationHandler;
use App\Scheduler\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler as NormalizedDataGenerationHandlerInterface;
use App\Scheduler\Handler\ChainHandlerAbstract;
use App\Scheduler\Message;
use App\StateMachine\Entity\Plan\PlanStatusStateMachine;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DefaultCalculationHandler extends ChainHandlerAbstract implements CalculationHandler
{

    public function __construct(
        private PlanStatusStateMachine $planStatusStateMachine,
        private MessageBusInterface $messageBus,
        private CountExecutor $calculatorExecutor,
        NormalizedDataGenerationHandlerInterface $normalizedDataGenerationHandler,
        LoggerInterface $logger
    ) {
        parent::__construct($normalizedDataGenerationHandler, $logger);
    }

    public function canHandle(Message $message): bool
    {
        return $this->planStatusStateMachine->can($message->getPlanId(), 'calculation_starting');
    }

    public function handle(Message $message) : void
    {
        $this->planStatusStateMachine->apply($message->getPlanId(), 'calculation_starting');

        try {
            ($this->calculatorExecutor)($message->getPlanId());
            $this->planStatusStateMachine->apply($message->getPlanId(), 'calculation_finishing');
        } catch (FeasibleSolutionNotFoundException $e) {
            $this->planStatusStateMachine->apply($message->getPlanId(), 'calculation_unsuccessful_marking');
        } catch (\Exception $e) {
            $this->planStatusStateMachine->apply($message->getPlanId(), 'calculation_error_marking');
            throw $e;
        }

        $this->messageBus->dispatch($message);
    }
}
