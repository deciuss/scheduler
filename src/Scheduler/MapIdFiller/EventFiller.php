<?php
namespace App\Scheduler\MapIdFiller;

use App\Entity\Plan;
use App\Repository\EventRepository;
use App\Repository\StudentGroupRepository;
use App\Scheduler\MapIdFiller;
use Doctrine\ORM\EntityManagerInterface;

class EventFiller implements MapIdFiller
{
    private EntityManagerInterface $entityManager;
    private EventRepository $eventRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventRepository $eventRepository,
        StudentGroupRepository $studentGroupRepository
    ) {
        $this->entityManager = $entityManager;
        $this->eventRepository = $eventRepository;
    }

    public function __invoke(Plan $plan) : void
    {
        $eventCounter = 0;
        foreach ($this->eventRepository->findByPlanOrderByIdAsc($plan) as $event) {
            $event->setMapId($eventCounter++);

        }
        $this->entityManager->flush();
    }

}