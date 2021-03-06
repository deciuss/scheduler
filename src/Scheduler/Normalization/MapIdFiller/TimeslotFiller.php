<?php

namespace App\Scheduler\Normalization\MapIdFiller;

use App\Repository\TimeslotRepository;
use App\Scheduler\Normalization\MapIdFiller;
use Doctrine\ORM\EntityManagerInterface;

class TimeslotFiller implements MapIdFiller
{
    private EntityManagerInterface $entityManager;
    private TimeslotRepository $timeslotRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TimeslotRepository $timeslotRepository
    ) {
        $this->entityManager = $entityManager;
        $this->timeslotRepository = $timeslotRepository;
    }

    public function __invoke(int $planId): void
    {
        $teacherCounter = 0;
        foreach ($this->timeslotRepository->findBy(['plan' => $planId], ['id' => 'ASC']) as $timeslot) {
            $timeslot->setMapId($teacherCounter++);
        }

        $this->entityManager->flush();
    }
}
