<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain;

use App\DBAL\PlanStatus;
use App\ChainHandler\ChainHandlerAbstract;
use App\Scheduler\Message\CalculateSchedule;
use App\Message\Message;
use App\Scheduler\NormalizedDataGenerator;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;


class NormalizedDataGenerationHandler extends ChainHandlerAbstract
{

    private MessageBusInterface $messageBus;
    private EntityManagerInterface $entityManager;
    private PlanRepository $planRepository;
    private NormalizedDataGenerator $normalizedDataGenerator;

    public function canHandle(Message $message): bool
    {
        return
            $message instanceof CalculateSchedule
            && in_array(
                $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
                [
                    PlanStatus::PLAN_STATUS_MAP_ID_FILLING_FINISHED
                ]
            );
    }

    public function __construct(
        MapIdFillingHandler $mapIdFillingHandler,
        LoggerInterface $logger,
        MessageBusInterface $messageBus,
        EntityManagerInterface $entityManager,
        PlanRepository $planRepository,
        NormalizedDataGenerator $normalizedDataGenerator
    ) {
        parent::__construct($mapIdFillingHandler, $logger);
        $this->messageBus = $messageBus;
        $this->entityManager = $entityManager;
        $this->planRepository = $planRepository;
        $this->normalizedDataGenerator = $normalizedDataGenerator;
    }

    public function handle(Message $message) : void
    {
        $plan = $this->planRepository->findOneBy(['id' => $message->getPlanId()]);

        $plan->setStatus(PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_STARTED);
        $this->entityManager->flush();

        $this->normalizedDataGenerator->generateNormalizedData($plan);

        $plan->setStatus(PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED);
        $this->entityManager->flush();

        $this->messageBus->dispatch($message);
    }
}