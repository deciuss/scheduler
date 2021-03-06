<?php

declare(strict_types=1);

namespace App\Tests\Context;

use App\DBAL\PlanStatus;
use App\Entity\Event;
use App\Entity\Plan;
use App\Entity\Room;
use App\Entity\StudentGroup;
use App\Entity\Subject;
use App\Entity\Teacher;
use App\Entity\Timeslot;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\PlanRepository;
use App\Repository\RoomRepository;
use App\Repository\StudentGroupRepository;
use App\Repository\SubjectRepository;
use App\Repository\TeacherRepository;
use App\Repository\TimeslotRepository;
use App\Scheduler\Handler\CalculateScheduleHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class SchedulerContext
{

    public function __construct(
        private ParameterBagInterface $parameterBag,
        private EntityManagerInterface $entityManager,
        private PlanRepository $planRepository,
        private SubjectRepository $subjectRepository,
        private EventRepository $eventRepository,
        private RoomRepository $roomRepository,
        private StudentGroupRepository $studentGroupRepository,
        private TeacherRepository $teacherRepository,
        private TimeslotRepository $timeslotRepository,
        private CalculateScheduleHandler $calculateScheduleHandler,
        private WorkflowInterface $planStatusStateMachine
    ) {}

    public function getParameterBag() : ParameterBagInterface
    {
        return $this->parameterBag;
    }

    public function getEntityManager() : EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function getPlanRepository() : PlanRepository
    {
        return $this->planRepository;
    }

    public function getSubjectRepository() : SubjectRepository
    {
        return $this->subjectRepository;
    }

    public function getEventRepository() : EventRepository
    {
        return $this->eventRepository;
    }

    public function getRoomRepository() : RoomRepository
    {
        return $this->roomRepository;
    }

    public function getStudentGroupRepository() : StudentGroupRepository
    {
        return $this->studentGroupRepository;
    }

    public function getTeacherRepository() : TeacherRepository
    {
        return $this->teacherRepository;
    }

    public function getTimeslotRepository() : TimeslotRepository
    {
        return $this->timeslotRepository;
    }

    public function getCalculateScheduleHandler() : CalculateScheduleHandler
    {
        return $this->calculateScheduleHandler;
    }

    public function getPlanStatusStateMachine() : WorkflowInterface
    {
        return $this->planStatusStateMachine;
    }

    public function givenPlanExists(
        string $name,
        string $status = PlanStatus::PLAN_STATUS_UNDER_CONSTRUCTION
    ) : Plan
    {
        $this->entityManager->persist(
            $user = (new User())
                ->setEmail(sprintf("%s@example.com", uniqid()))
                ->setPassword("password")
        );

        $this->entityManager->persist(
            $plan = (new Plan())
                ->setName($name)
                ->setStatus($status)
                ->setUser($user)
        );

        $this->entityManager->flush();
        return $plan;
    }

    public function givenRoomExists(string $name, Plan $plan): Room
    {
        $this->entityManager->persist(
            $room = (new Room())
                ->setPlan($plan)
                ->setName($name)
            ->setCapacity(1)
        );

        $this->entityManager->flush();
        return $room;
    }

    public function givenTeacherExists(string $name, Plan $plan): Teacher
    {
        $this->entityManager->persist(
            $teacher = (new Teacher())
                ->setPlan($plan)
                ->setName($name)
        );

        $this->entityManager->flush();
        return $teacher;
    }

    public function givenStudentGroupExists(string $name, Plan $plan): StudentGroup
    {
        $this->entityManager->persist(
            $studentGroup = (new StudentGroup())
                ->setPlan($plan)
                ->setCardinality(1)
                ->setName($name)
        );

        $this->entityManager->flush();
        return $studentGroup;
    }

    public function givenSubjectExists(string $name, Plan $plan, Teacher $teacher, StudentGroup $studentGroup, int $hours = 1): Subject
    {
        $this->entityManager->persist(
            $subject = (new Subject())
                ->setPlan($plan)
                ->setTeacher($teacher)
                ->setStudentGroup($studentGroup)
                ->setName($name)
                ->setHours($hours)
                ->setBlockSize(1)
        );

        $this->entityManager->flush();
        return $subject;
    }

    public function givenEventExists(string $name, Subject $subject): Event
    {
        $this->entityManager->persist(
            $event = (new Event())
                ->setSubject($subject)
        );

        $this->entityManager->flush();
        return $event;
    }

    public function givenTimeslotExists(Plan $plan): Timeslot
    {
        $this->entityManager->persist(
            $timeslot = (new Timeslot())
                ->setPlan($plan)
                ->setStart(new \DateTimeImmutable('2000-01-01 00:00:00'))
                ->setEnd(new \DateTimeImmutable('2000-01-01 00:00:00'))
        );

        $this->entityManager->flush();
        return $timeslot;
    }

}