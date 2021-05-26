<?php
namespace App\Scheduler\Normalization\MapIdFiller;

use App\Entity\Plan;
use App\Repository\StudentGroupRepository;
use App\Scheduler\Normalization\MapIdFiller;
use Doctrine\ORM\EntityManagerInterface;

class StudentGroupFiller implements MapIdFiller
{
    private EntityManagerInterface $entityManager;
    private StudentGroupRepository $studentGroupRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        StudentGroupRepository $studentGroupRepository
    ) {
        $this->entityManager = $entityManager;
        $this->studentGroupRepository = $studentGroupRepository;
    }

    public function __invoke(int $planId) : void
    {
        $studentGroupCounter = 0;
        foreach ($this->studentGroupRepository->findBy(['plan' => $planId], ['id' => 'ASC']) as $studentGroup) {
            $studentGroup->setMapId($studentGroupCounter++);
        }

        $this->entityManager->flush();
    }

}