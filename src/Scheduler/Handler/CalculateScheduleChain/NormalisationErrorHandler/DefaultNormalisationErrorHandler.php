<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain\NormalisationErrorHandler;

use App\DBAL\PlanStatus;
use App\Scheduler\Handler\CalculateScheduleChain\NormalisationErrorHandler as NormalisationErrorHandlerInterface;
use App\Scheduler\Handler\ChainHandlerAbstract;
use App\Scheduler\Message;
use App\Scheduler\Message\CalculateSchedule;
use App\StateMachine\Entity\Plan\PlanStatusStateMachine;
use Psr\Log\LoggerInterface;

class DefaultNormalisationErrorHandler extends ChainHandlerAbstract implements NormalisationErrorHandlerInterface
{
    public function __construct(
        private PlanStatusStateMachine $planStatusStateMachine,
        LoggerInterface $logger
    ) {
        parent::__construct(null, $logger);
    }

    public function canHandle(Message $message): bool
    {
        if (!$message instanceof CalculateSchedule) {
            return false;
        }

        return $this->planStatusStateMachine->is($message->getPlanId(), PlanStatus::PLAN_STATUS_NORMALISATION_ERROR);
    }

    /**
     * @todo
     */
    public function handle(Message $message): void
    {
        throw new \RuntimeException(sprintf('Normalisation for plan %d failed.', $message->getPlanId()));
    }
}
