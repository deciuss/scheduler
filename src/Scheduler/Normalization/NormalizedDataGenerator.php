<?php

declare(strict_types=1);

namespace App\Scheduler\Normalization;

use App\Repository\EventRepository;
use App\Repository\PlanRepository;
use App\Repository\RoomRepository;
use App\Repository\StudentGroupRepository;
use App\Repository\SubjectRepository;
use App\Repository\TeacherRepository;
use App\Repository\TimeslotRepository;
use App\Scheduler\Normalization\Generator\EventBlock;
use App\Scheduler\Normalization\Generator\EventGroups;
use App\Scheduler\Normalization\Generator\EventRoomFit;
use App\Scheduler\Normalization\Generator\EventTeacher;
use App\Scheduler\Normalization\Generator\EventTimeslotShare;
use App\Scheduler\Normalization\Generator\TimeslotNeighborNext;

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

    public function generateNormalizedData(int $planId): void
    {
        $writer = $this->writerFactory->create((string) $planId);

        $events = $this->eventRepository->findByPlanIdOrderByIdAsc($planId);
        $eventBlockGenerated = $this->eventBlock->generate(...$this->subjectRepository->findBy(['plan' => $planId], ['id' => 'asc']));

        $writer->appendInt($this->eventRepository->countByPlanId($planId));
        $writer->appendInt($this->roomRepository->count(['plan' => $planId]));
        $writer->appendInt($this->timeslotRepository->count(['plan' => $planId]));
        $writer->appendInt($this->studentGroupRepository->count(['plan' => $planId]));
        $writer->appendInt($this->teacherRepository->count(['plan' => $planId]));
        $writer->appendInt(count($eventBlockGenerated));
        $writer->appendIntOneToMany($eventBlockGenerated);
        $writer->appendBoolMatrix($this->eventTimeslotShare->generate(...$events));

        $writer->appendBoolMatrix(
            $this->eventRoomFit->generate(
                $events,
                $this->roomRepository->findBy(['plan' => $planId], ['id' => 'asc'])
            )
        );

        $writer->appendIntArray(
            $this->timeslotNeighborNext->generate(
                ...$this->timeslotRepository->findBy(['plan' => $planId], ['id' => 'asc'])
            )
        );

        $writer->appendIntOneToMany($this->eventGroups->generate(...$events));
        $writer->appendIntArray($this->eventTeacher->generate(...$events));
    }
}
