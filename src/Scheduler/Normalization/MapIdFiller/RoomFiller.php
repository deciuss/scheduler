<?php

namespace App\Scheduler\Normalization\MapIdFiller;

use App\Repository\RoomRepository;
use App\Scheduler\Normalization\MapIdFiller;
use Doctrine\ORM\EntityManagerInterface;

class RoomFiller implements MapIdFiller
{
    private EntityManagerInterface $entityManager;
    private RoomRepository $roomRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        RoomRepository $roomRepository
    ) {
        $this->entityManager = $entityManager;
        $this->roomRepository = $roomRepository;
    }

    public function __invoke(int $planId): void
    {
        $roomCounter = 0;
        foreach ($this->roomRepository->findBy(['plan' => $planId], ['id' => 'ASC']) as $room) {
            $room->setMapId($roomCounter++);
        }

        $this->entityManager->flush();
    }
}
