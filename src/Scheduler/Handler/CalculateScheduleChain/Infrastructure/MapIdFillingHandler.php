<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain\Infrastructure;

use App\DBAL\PlanStatus;
use App\Scheduler\Handler\ChainHandlerAbstract;
use App\Scheduler\Message;
use App\Scheduler\Message\CalculateSchedule;
use App\Scheduler\MapIdFiller;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Scheduler\Handler\CalculateScheduleChain\MapIdFillingHandler as MapIdFillingHandlerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\EventFillingHandler as EventFillingHandlerInterface;

class MapIdFillingHandler extends ChainHandlerAbstract implements MapIdFillingHandlerInterface
{
    private MessageBusInterface $messageBus;
    private EntityManagerInterface $entityManager;
    private PlanRepository $planRepository;

    /**
     * @var MapIdFiller[]
     */
    private array $mapIdFillers;

    public function canHandle(Message $message): bool
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
        EventFillingHandlerInterface $eventFillingHandler,
        LoggerInterface $logger,
        MessageBusInterface $messageBus,
        EntityManagerInterface $entityManager,
        PlanRepository $planRepository,
        MapIdFiller\EventFiller $eventFiller,
        MapIdFiller\RoomFiller $roomFiller,
        MapIdFiller\StudentGroupFiller $studentGroupFiller,
        MapIdFiller\TeacherFiller $teacherFiller,
        MapIdFiller\TimeslotFiller $timeslotFiller
    ) {
        parent::__construct($eventFillingHandler, $logger);
        $this->messageBus = $messageBus;
        $this->entityManager = $entityManager;
        $this->planRepository = $planRepository;

        $this->mapIdFillers = [
            $eventFiller,
            $roomFiller,
            $studentGroupFiller,
            $teacherFiller,
            $timeslotFiller
        ];
    }

    public function handle(Message $message) : void
    {
        $plan = $this->planRepository->findOneBy(['id' => $message->getPlanId()]);

        $plan->setStatus(PlanStatus::PLAN_STATUS_MAP_ID_FILLING_STARTED);
        $this->entityManager->flush();

        foreach ($this->mapIdFillers as $mapIdFiller) {
            $mapIdFiller($plan);
        }

        $plan->setStatus(PlanStatus::PLAN_STATUS_MAP_ID_FILLING_FINISHED);
        $this->entityManager->flush();

        $this->messageBus->dispatch($message);
    }
}