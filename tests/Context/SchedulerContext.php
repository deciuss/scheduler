<?php

declare(strict_types=1);

namespace App\Tests\Context;

use App\DBAL\PlanStatus;
use App\Entity\Plan;
use App\Entity\Teacher;
use App\Entity\User;
use App\Repository\TeacherRepository;
use Doctrine\ORM\EntityManagerInterface;

class SchedulerContext
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

    public function getEntityManager() : EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function getTeacherRepository() : TeacherRepository
    {
        return $this->teacherRepository;
    }

    public function givenPlanExists(string $name) : Plan
    {
        $this->entityManager->persist(
            $user = (new User())
                ->setEmail("user@example.com")
                ->setPassword("password")
        );

        $this->entityManager->persist(
            $plan = (new Plan())
                ->setName($name)
                ->setStatus(PlanStatus::PLAN_STATUS_UNDER_CONSTRUCTION)
                ->setUser($user)
        );

        $this->entityManager->flush();
        return $plan;
    }

    public function givenTeacherForPlanExists(string $name, Plan $plan): Teacher
    {
        $this->entityManager->persist(
            $teacher = (new Teacher())
                ->setPlan($plan)
                ->setName($name)
        );

        $this->entityManager->flush();
        return $teacher;
    }

}