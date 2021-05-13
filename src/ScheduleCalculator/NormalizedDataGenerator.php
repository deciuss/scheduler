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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\File;

class NormalizedDataGenerator
{
    private string $dataPath;

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

    private DataEncoder $dataEncoder;

    public function __construct(
        ParameterBagInterface $parameterBag,
        EventTimeslotShare $eventTimeslotShare,
        EventRoomFit $eventRoomFit,
        EventBlock $eventBlock,
        TimeslotNeighborNext $timeslotNeighborNext,
        EventGroups $eventGroups,
        EventTeacher $eventTeacher,
        DataEncoder $dataEncoder,
        EventRepository $eventRepository,
        RoomRepository $roomRepository,
        TimeslotRepository $timeslotRepository,
        StudentGroupRepository $studentGroupRepository,
        TeacherRepository $teacherRepository,
        PlanRepository $planRepository,
        SubjectRepository $subjectRepository
    ) {
        $this->dataPath = $parameterBag->get('scheduler.calculator.data_path');

        $this->dataEncoder = $dataEncoder;

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
        $calculatorFilePathName = sprintf(
            "%s/%s",
            $this->dataPath,
            $plan->getId()
        );

        touch($calculatorFilePathName);

        $events = $this->eventRepository->findByPlanOrderByIdAsc($plan);
        $eventBlockGenerated = $this->eventBlock->generate(...$this->subjectRepository->findBy(['plan' => $plan], ['id' => 'asc']));

        file_put_contents(
            $calculatorFilePathName,
            $this->dataEncoder->encodeInt($this->eventRepository->countByPlan($plan)),
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->dataEncoder->encodeInt($this->roomRepository->count(['plan' => $plan])),
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->dataEncoder->encodeInt($this->timeslotRepository->count(['plan' => $plan])),
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->dataEncoder->encodeInt($this->studentGroupRepository->count(['plan' => $plan])),
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->dataEncoder->encodeInt($this->teacherRepository->count(['plan' => $plan])),
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->dataEncoder->encodeInt(count($eventBlockGenerated)),
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->dataEncoder->encodeIntOneToMany(
                $eventBlockGenerated
            ),
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->dataEncoder->encodeBoolMatrix(
                $this->eventTimeslotShare->generate(...$events)
            ),
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->dataEncoder->encodeBoolMatrix(
                $this->eventRoomFit->generate(
                    $events,
                    $this->roomRepository->findBy(['plan' => $plan], ['id' => 'asc'])
                )
            ),
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->dataEncoder->encodeIntArray(
                $this->timeslotNeighborNext->generate(
                    ...$this->timeslotRepository->findBy(['plan' => $plan], ['id' => 'asc'])
                )
            ),
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->dataEncoder->encodeIntOneToMany(
                $this->eventGroups->generate(...$events)
            ),
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->dataEncoder->encodeIntArray(
                $this->eventTeacher->generate(...$events)
            ),
            FILE_APPEND
        );

    }

}