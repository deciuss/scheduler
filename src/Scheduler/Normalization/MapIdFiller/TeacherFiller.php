<?php

namespace App\Scheduler\Normalization\MapIdFiller;

use App\Repository\TeacherRepository;
use App\Scheduler\Normalization\MapIdFiller;
use Doctrine\ORM\EntityManagerInterface;

class TeacherFiller implements MapIdFiller
{
    private EntityManagerInterface $entityManager;
    private TeacherRepository $teacherRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TeacherRepository $teacherRepository
    ) {
        $this->entityManager = $entityManager;
        $this->teacherRepository = $teacherRepository;
    }

    public function __invoke(int $planId): void
    {
        $teacherCounter = 0;
        foreach ($this->teacherRepository->findBy(['plan' => $planId], ['id' => 'ASC']) as $teacher) {
            $teacher->setMapId($teacherCounter++);
        }

        $this->entityManager->flush();
    }
}
