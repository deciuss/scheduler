<?php


namespace App\ScheduleCalculator\Handler\CalculateScheduleChain;


use App\DBAL\PlanStatus;
use App\ChainHandler\ChainHandler;
use App\ScheduleCalculator\Message\CalculateSchedule;
use App\Message\Message;
use App\ScheduleCalculator\NormalizedDataGenerator;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;


class NormalizedDataGenerationHandler extends ChainHandler
{

    private MessageBusInterface $messageBus;
    private EntityManagerInterface $entityManager;
    private PlanRepository $planRepository;
    private NormalizedDataGenerator $normalizedDataGenerator;

    protected function canHandle(Message $message): bool
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

    protected function handle(Message $message) : void
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