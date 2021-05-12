<?php
namespace App\ScheduleCalculator;

use App\Entity\Event;
use App\Entity\Plan;
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

    public function fillEvents(Plan $plan) : void
    {
        foreach ($this->subjectRepository->findBy(['plan' => $plan], ['id' => 'asc']) as $subject) {
            for($i = 0; $i < $subject->getHours(); $i++) {
                $this->entityManager->persist((new Event())->setSubject($subject));
            }
        }

        $this->entityManager->flush();
    }

}