<?php


namespace App\Handler\CalculateScheduleChain;


use App\DBAL\PlanStatus;
use App\Handler\ChainedHandler;
use App\Message\CalculateSchedule;
use App\Message\Message;
use App\Normalisation\EventFiller;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;


class EventFillingHandler extends ChainedHandler
{

    private MessageBusInterface $messageBus;
    private EntityManagerInterface $entityManager;
    private PlanRepository $planRepository;
    private EventFiller $eventFiller;


    protected function canHandle(Message $message): bool
    {
        return in_array(
            $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
            [
                PlanStatus::PLAN_STATUS_LOCKED
            ]
        );
    }

    public function __construct(
        LockHandler $lockHandler,
        LoggerInterface $logger,
        MessageBusInterface $messageBus,
        EntityManagerInterface $entityManager,
        PlanRepository $planRepository,
        EventFiller $eventFiller
    ) {
        parent::__construct($lockHandler, $logger);
        $this->messageBus = $messageBus;
        $this->entityManager = $entityManager;
        $this->planRepository = $planRepository;
        $this->eventFiller = $eventFiller;
    }

    public function __invoke(CalculateSchedule $message) : void
    {
        if (! $this->canHandle($message)) {
            $this->invokeNextHandler($message);
            return;
        }

        $this->logger->info(sprintf('%s started handling message: %s %s', get_class($this), get_class($message), json_encode($message)));

        $plan = $this->planRepository->findOneBy(['id' => $message->getPlanId()]);

        $plan->setStatus(PlanStatus::PLAN_STATUS_EVENT_FILLING_STARTED);
        $this->entityManager->flush();

        $this->eventFiller->fillEvents($plan);

        $plan->setStatus(PlanStatus::PLAN_STATUS_EVENT_FILLING_FINISHED);
        $this->entityManager->flush();

        $this->messageBus->dispatch($message);

        $this->logger->info(sprintf('%s finished handling message: %s %s', get_class($this), get_class($message), json_encode($message)));
    }
}