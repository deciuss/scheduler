<?php
namespace App\ScheduleCalculator;

use App\Entity\Event;
use App\Entity\Plan;
use App\Repository\EventRepository;
use App\Repository\RoomRepository;
use App\Repository\StudentGroupRepository;
use App\Repository\SubjectRepository;
use App\Repository\TeacherRepository;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;

class MapIdFiller
{

    private EntityManagerInterface $entityManager;
    private EventRepository $eventRepository;
    private StudentGroupRepository $studentGroupRepository;
    private TimeslotRepository $timeslotRepository;
    private TeacherRepository $teacherRepository;
    private RoomRepository $roomRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventRepository $eventRepository,
        StudentGroupRepository $studentGroupRepository,
        TimeslotRepository $timeslotRepository,
        TeacherRepository $teacherRepository,
        RoomRepository $roomRepository
    ) {
        $this->entityManager = $entityManager;
        $this->eventRepository = $eventRepository;
        $this->studentGroupRepository = $studentGroupRepository;
        $this->timeslotRepository = $timeslotRepository;
        $this->teacherRepository = $teacherRepository;
        $this->roomRepository = $roomRepository;
    }

    public function fillMapIds(Plan $plan) : void
    {

        $eventCounter = 0;
        foreach ($this->eventRepository->findByPlanOrderByIdAsc($plan) as $event) {
            $event->setMapId($eventCounter++);
        }

        $studentGroupCounter = 0;
        foreach ($this->studentGroupRepository->findBy(['plan' => $plan], ['id' => 'ASC']) as $studentGroup) {
            $studentGroup->setMapId($studentGroupCounter++);
        }

        $timeslotCounter = 0;
        foreach ($this->timeslotRepository->findBy(['plan' => $plan], ['id' => 'ASC']) as $timeslot) {
            $timeslot->setMapId($timeslotCounter++);
        }

        $teacherCounter = 0;
        foreach ($this->teacherRepository->findBy(['plan' => $plan], ['id' => 'ASC']) as $teacher) {
            $teacher->setMapId($teacherCounter++);
        }

        $roomCounter = 0;
        foreach ($this->roomRepository->findBy(['plan' => $plan], ['id' => 'ASC']) as $room) {
            $room->setMapId($roomCounter++);
        }

        $this->entityManager->flush();
    }

}