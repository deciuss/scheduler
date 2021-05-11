<?php


namespace App\Handler\CalculateScheduleChain;


use App\DBAL\PlanStatus;
use App\Handler\ChainedHandler;
use App\Message\CalculateSchedule;
use App\Message\Message;
use App\Normalisation\NormalizedDataGenerator;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;


class NormalizedDataGenerationHandler extends ChainedHandler
{

    private MessageBusInterface $messageBus;
    private EntityManagerInterface $entityManager;
    private PlanRepository $planRepository;
    private NormalizedDataGenerator $normalizedDataGenerator;

    protected function canHandle(Message $message): bool
    {
        return in_array(
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

    public function __invoke(CalculateSchedule $message)
    {
        if (! $this->canHandle($message)) {
            return $this->invokeNextHandler($message);

            $this->logger->info(sprintf('%s started handling message: %s %s', get_class($this), get_class($message), json_encode($message)));

            $plan = $this->planRepository->findOneBy(['id' => $message->getPlanId()]);

            $plan->setStatus(PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_STARTED);
            $this->entityManager->flush();

            $this->normalizedDataGenerator->generateNormalizedData($plan);

            $plan->setStatus(PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED);
            $this->entityManager->flush();

            $this->messageBus->dispatch($message);

            $this->logger->info(sprintf('%s finished handling message: %s %s', get_class($this), get_class($message), json_encode($message)));
        }
    }
}