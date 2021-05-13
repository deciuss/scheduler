<?php

declare(strict_types=1);

namespace App\ScheduleCalculator;

use App\Entity\Plan;
use App\Repository\SubjectRepository;
use App\ScheduleCalculator\Generator\EventBlock;
use App\ScheduleCalculator\Generator\EventGroups;
use App\ScheduleCalculator\Generator\EventRoomFit;
use App\ScheduleCalculator\Generator\EventTeacher;
use App\ScheduleCalculator\Generator\EventTimeslotShare;
use App\ScheduleCalculator\Generator\TimeslotNeighborNext;
use App\Repository\EventRepository;
use App\Repository\PlanRepository;
use App\Repository\RoomRepository;
use App\Repository\StudentGroupRepository;
use App\Repository\TeacherRepository;
use App\Repository\TimeslotRepository;

class NormalizedDataGenerator
{
    private WriterFactory $writerFactory;

    private EventRepository $eventRepository;
    private RoomRepository $roomRepository;
    private TimeslotRepository $timeslotRepository;
    private StudentGroupRepository $studentGroupRepository;
    private TeacherRepository $teacherRepository;
    private PlanRepository $planRepository;
    private SubjectRepository $subjectRepository;

    private EventBlock $eventBlock;
    private EventTimeslotShare $eventTimeslotShare;
    private EventRoomFit $eventRoomFit;
    private TimeslotNeighborNext $timeslotNeighborNext;
    private EventGroups $eventGroups;
    private EventTeacher $eventTeacher;

    public function __construct(
        WriterFactory $writerFactory,
        EventRepository $eventRepository,
        RoomRepository $roomRepository,
        TimeslotRepository $timeslotRepository,
        StudentGroupRepository $studentGroupRepository,
        TeacherRepository $teacherRepository,
        PlanRepository $planRepository,
        SubjectRepository $subjectRepository,
        EventTimeslotShare $eventTimeslotShare,
        EventRoomFit $eventRoomFit,
        EventBlock $eventBlock,
        TimeslotNeighborNext $timeslotNeighborNext,
        EventGroups $eventGroups,
        EventTeacher $eventTeacher
    ) {
        $this->writerFactory = $writerFactory;

        $this->eventRepository = $eventRepository;
        $this->roomRepository = $roomRepository;
        $this->timeslotRepository = $timeslotRepository;
        $this->studentGroupRepository = $studentGroupRepository;
        $this->teacherRepository = $teacherRepository;
        $this->planRepository = $planRepository;
        $this->subjectRepository = $subjectRepository;

        $this->eventBlock = $eventBlock;
        $this->eventTimeslotShare = $eventTimeslotShare;
        $this->eventRoomFit = $eventRoomFit;
        $this->timeslotNeighborNext = $timeslotNeighborNext;
        $this->eventGroups = $eventGroups;
        $this->eventTeacher = $eventTeacher;
    }

    public function generateNormalizedData(Plan $plan) : void
    {
        $writer = $this->writerFactory->create((string) $plan->getId());

        $events = $this->eventRepository->findByPlanOrderByIdAsc($plan);
        $eventBlockGenerated = $this->eventBlock->generate(...$this->subjectRepository->findBy(['plan' => $plan], ['id' => 'asc']));

        $writer->appendInt($this->eventRepository->countByPlan($plan));
        $writer->appendInt($this->roomRepository->count(['plan' => $plan]));
        $writer->appendInt($this->timeslotRepository->count(['plan' => $plan]));
        $writer->appendInt($this->studentGroupRepository->count(['plan' => $plan]));
        $writer->appendInt($this->teacherRepository->count(['plan' => $plan]));
        $writer->appendInt(count($eventBlockGenerated));
        $writer->appendIntOneToMany($eventBlockGenerated);
        $writer->appendBoolMatrix($this->eventTimeslotShare->generate(...$events));

        $writer->appendBoolMatrix(
            $this->eventRoomFit->generate(
                $events,
                $this->roomRepository->findBy(['plan' => $plan], ['id' => 'asc'])
            )
        );

        $writer->appendIntArray(
            $this->timeslotNeighborNext->generate(
                ...$this->timeslotRepository->findBy(['plan' => $plan], ['id' => 'asc'])
            )
        );

        $writer->appendIntOneToMany($this->eventGroups->generate(...$events));
        $writer->appendIntArray($this->eventTeacher->generate(...$events));
    }

}