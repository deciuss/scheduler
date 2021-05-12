<?php


namespace App\ScheduleCalculator\Handler\CalculateScheduleChain;


use App\DBAL\PlanStatus;
use App\ChainHandler\ChainHandler;
use App\Message\Message;
use App\ScheduleCalculator\Message\CalculateSchedule;
use App\ScheduleCalculator\MapIdFiller;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;


class MapIdFillingHandler extends ChainHandler
{

    private MessageBusInterface $messageBus;
    private EntityManagerInterface $entityManager;
    private PlanRepository $planRepository;
    private MapIdFiller $mapIdFiller;

    protected function canHandle(Message $message): bool
    {
        return
            $message instanceof CalculateSchedule
            && in_array(
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

    protected function handle(Message $message) : void
    {
        $plan = $this->planRepository->findOneBy(['id' => $message->getPlanId()]);

        $plan->setStatus(PlanStatus::PLAN_STATUS_MAP_ID_FILLING_STARTED);
        $this->entityManager->flush();

        $this->mapIdFiller->fillMapIds($plan);

        $plan->setStatus(PlanStatus::PLAN_STATUS_MAP_ID_FILLING_FINISHED);
        $this->entityManager->flush();

        $this->messageBus->dispatch($message);
    }
}