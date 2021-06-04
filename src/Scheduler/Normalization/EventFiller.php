<?php

namespace App\Scheduler\Normalization;

use App\Entity\Event;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class EventFiller
{
    private EntityManagerInterface $entityManager;

    private SubjectRepository $subjectRepository;

    public function __construct(EntityManagerInterface $entityManager, SubjectRepository $subjectRepository)
    {
        $this->entityManager = $entityManager;
        $this->subjectRepository = $subjectRepository;
    }

    public function __invoke(int $planId): void
    {
        foreach ($this->subjectRepository->findBy(['plan' => $planId], ['id' => 'asc']) as $subject) {
            for ($i = 0; $i < $subject->getHours(); ++$i) {
                $this->entityManager->persist((new Event())->setSubject($subject));
            }
        }

        $this->entityManager->flush();
    }
}
