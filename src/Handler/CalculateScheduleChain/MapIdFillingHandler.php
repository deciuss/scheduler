<?php


namespace App\Handler\CalculateScheduleChain;


use App\DBAL\PlanStatus;
use App\Handler\ChainedHandler;
use App\Message\Message;
use App\Message\CalculateSchedule;
use App\Normalisation\MapIdFiller;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;


class MapIdFillingHandler extends ChainedHandler
{

    private MessageBusInterface $messageBus;
    private EntityManagerInterface $entityManager;
    private PlanRepository $planRepository;
    private MapIdFiller $mapIdFiller;

    protected function canHandle(Message $message): bool
    {
        return in_array(
            $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
            [
                PlanStatus::PLAN_STATUS_EVENT_FILLING_FINISHED
            ]
        );
    }

    public function __construct(
        EventFillingHandler $eventFillingHandler,
        LoggerInterface $logger,
        MessageBusInterface $messageBus,
        EntityManagerInterface $entityManager,
        PlanRepository $planRepository,
        MapIdFiller $mapIdFiller
    ) {
        parent::__construct($eventFillingHandler, $logger);
        $this->messageBus = $messageBus;
        $this->entityManager = $entityManager;
        $this->planRepository = $planRepository;
        $this->mapIdFiller = $mapIdFiller;
    }

    public function __invoke(CalculateSchedule $message)
    {
        if (! $this->canHandle($message)) {
            return $this->invokeNextHandler($message);
        }

        $this->logger->info(sprintf('%s started handling message: %s %s', get_class($this), get_class($message), json_encode($message)));

        $plan = $this->planRepository->findOneBy(['id' => $message->getPlanId()]);

        $plan->setStatus(PlanStatus::PLAN_STATUS_MAP_ID_FILLING_STARTED);
        $this->entityManager->flush();

        $this->mapIdFiller->fillMapIds($plan);

        $plan->setStatus(PlanStatus::PLAN_STATUS_MAP_ID_FILLING_FINISHED);
        $this->entityManager->flush();

        $this->messageBus->dispatch($message);

        $this->logger->info(sprintf('%s finished handling message: %s %s', get_class($this), get_class($message), json_encode($message)));
    }
}