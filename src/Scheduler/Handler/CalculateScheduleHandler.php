<?php

declare(strict_types=1);

namespace App\Scheduler\Handler;

use App\DBAL\PlanStatus;
use App\Scheduler\CalculatorExecutor;
use App\Scheduler\Handler\ChainHandlerAbstract;
use App\Scheduler\Message\CalculateSchedule;
use App\Scheduler\Message;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Uri\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler as NormalizedDataGenerationHandlerInterface;

class CalculateScheduleHandler extends ChainHandlerAbstract implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private PlanRepository $planRepository;
    private CalculatorExecutor $calculatorExecutor;

    public function canHandle(Message $message): bool
    {
        return in_array(
            $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
            [
                PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED,
                PlanStatus::PLAN_STATUS_SCHEDULE_CALCULATION_FINISHED
            ]
        );
    }

    public function __construct(
        NormalizedDataGenerationHandlerInterface $normalizedDataGenerationHandler,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        PlanRepository $planRepository,
        CalculatorExecutor $calculatorExecutor
    ) {
        parent::__construct($normalizedDataGenerationHandler, $logger);
        $this->entityManager = $entityManager;
        $this->planRepository = $planRepository;
        $this->calculatorExecutor = $calculatorExecutor;
    }

    public function handle(Message $message) : void
    {
        $plan = $this->planRepository->findOneBy(['id' => $message->getPlanId()]);

        $plan->setStatus(PlanStatus::PLAN_STATUS_SCHEDULE_CALCULATION_STARTED);
        $this->entityManager->flush();

        try {
            ($this->calculatorExecutor)((string) $message->getPlanId());
            $plan->setStatus(PlanStatus::PLAN_STATUS_SCHEDULE_CALCULATION_FINISHED);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $plan->setStatus(PlanStatus::PLAN_STATUS_CALCULATION_ERROR);
            $this->entityManager->flush();
            throw $e;
        }
    }

    public function __invoke(CalculateSchedule $message)
    {
        $this->executeHandler($message);
    }
}