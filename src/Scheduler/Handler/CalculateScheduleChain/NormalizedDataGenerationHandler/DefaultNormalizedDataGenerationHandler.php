<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler;

use App\DBAL\PlanStatus;
use App\Scheduler\Handler\ChainHandlerAbstract;
use App\Scheduler\Message\CalculateSchedule;
use App\Scheduler\Message;
use App\Scheduler\Normalization\NormalizedDataGenerator;
use App\Repository\PlanRepository;
use App\StateMachine\Entity\Plan\PlanStatusStateMachine;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Scheduler\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler as NormalizedDataGenerationHandlerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\MapIdFillingHandler as MapIdFillingHandlerInterface;

class DefaultNormalizedDataGenerationHandler extends ChainHandlerAbstract implements NormalizedDataGenerationHandlerInterface
{

    public function __construct(
        private PlanStatusStateMachine $planStatusStateMachine,
        private MessageBusInterface $messageBus,
        private NormalizedDataGenerator $normalizedDataGenerator,
        LoggerInterface $logger,
        MapIdFillingHandlerInterface $mapIdFillingHandler
    ) {
        parent::__construct($mapIdFillingHandler, $logger);

    }

    public function canHandle(Message $message): bool
    {
        if (! $message instanceof CalculateSchedule) {
            return false;
        }

        return $this->planStatusStateMachine->can($message->getPlanId(),'normalized_data_generation_starting');
    }

    public function handle(Message $message) : void
    {
        $this->planStatusStateMachine->apply($message->getPlanId(),'normalized_data_generation_starting');
        $this->normalizedDataGenerator->generateNormalizedData($message->getPlanId());
        $this->planStatusStateMachine->apply($message->getPlanId(),'normalized_data_generation_finishing');
        $this->messageBus->dispatch($message);
    }
}