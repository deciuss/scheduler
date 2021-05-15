<?php
namespace App\Scheduler\MapIdFiller;

use App\Entity\Plan;
use App\Repository\RoomRepository;
use App\Scheduler\MapIdFiller;
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

    public function __invoke(Plan $plan) : void
    {
        $roomCounter = 0;
        foreach ($this->roomRepository->findBy(['plan' => $plan], ['id' => 'ASC']) as $room) {
            $room->setMapId($roomCounter++);
        }

        $this->entityManager->flush();
    }

}