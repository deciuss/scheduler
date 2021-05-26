<?php
namespace App\Scheduler\Normalization\MapIdFiller;

use App\Entity\Plan;
use App\Repository\EventRepository;
use App\Repository\StudentGroupRepository;
use App\Scheduler\Normalization\MapIdFiller;
use Doctrine\ORM\EntityManagerInterface;

class EventFiller implements MapIdFiller
{
    private EntityManagerInterface $entityManager;
    private EventRepository $eventRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventRepository $eventRepository
    ) {
        $this->entityManager = $entityManager;
        $this->eventRepository = $eventRepository;
    }

    public function __invoke(int $planId) : void
    {
        $eventCounter = 0;
        foreach ($this->eventRepository->findByPlanIdOrderByIdAsc($planId) as $event) {
            $event->setMapId($eventCounter++);

        }
        $this->entityManager->flush();
    }

}