<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain\Infrastructure;

use App\DBAL\PlanStatus;
use App\ChainHandler\ChainHandlerAbstract;
use App\Scheduler\Message\CalculateSchedule;
use App\Message\Message;
use App\Scheduler\EventFiller;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Scheduler\Handler\CalculateScheduleChain\EventFillingHandler as EventFillingHandlerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\LockHandler as LockHandlerInterface;

class EventFillingHandler extends ChainHandlerAbstract implements EventFillingHandlerInterface
{
    private MessageBusInterface $messageBus;
    private EntityManagerInterface $entityManager;
    private PlanRepository $planRepository;
    private EventFiller $eventFiller;


    public function canHandle(Message $message): bool
    {
        return
            $message instanceof CalculateSchedule
            && in_array(
                $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
                [
                    PlanStatus::PLAN_STATUS_LOCKED
                ]
            );
    }

    public function __construct(
        LockHandlerInterface $lockHandler,
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

    public function handle(Message $message) : void
    {
        $plan = $this->planRepository->findOneBy(['id' => $message->getPlanId()]);

        $plan->setStatus(PlanStatus::PLAN_STATUS_EVENT_FILLING_STARTED);
        $this->entityManager->flush();

        ($this->eventFiller)($plan);

        $plan->setStatus(PlanStatus::PLAN_STATUS_EVENT_FILLING_FINISHED);
        $this->entityManager->flush();

        $this->messageBus->dispatch($message);
    }
}