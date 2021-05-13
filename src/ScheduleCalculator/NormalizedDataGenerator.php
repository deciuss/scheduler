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

class NormalizedDataGenerator
{

    private string $dataPath;

    /**
     * @var Generator[]
     */
    private array $generators = [];

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

    private MatrixFlattener $matrixFlattener;

    public function __construct(
        ParameterBagInterface $parameterBag,
        EventTimeslotShare $eventTimeslotShare,
        EventRoomFit $eventRoomFit,
        EventBlock $eventBlock,
        TimeslotNeighborNext $timeslotNeighborNext,
        EventGroups $eventGroups,
        EventTeacher $eventTeacher,
        MatrixFlattener $matrixFlattener,
        EventRepository $eventRepository,
        RoomRepository $roomRepository,
        TimeslotRepository $timeslotRepository,
        StudentGroupRepository $studentGroupRepository,
        TeacherRepository $teacherRepository,
        PlanRepository $planRepository,
        SubjectRepository $subjectRepository
    ) {
        $this->dataPath = $parameterBag->get('scheduler.calculator.data_path');

//        $this->generators[] = $eventBlock;
//        $this->generators[] = $eventTimeslotShare;
//        $this->generators[] = $eventRoomFit;
//        $this->generators[] = $timeslotNeighborNext;
//        $this->generators[] = $eventGroups;
//        $this->generators[] = $eventTeacher;


        $this->eventBlock = $eventBlock;
        $this->eventTimeslotShare = $eventTimeslotShare;
        $this->eventRoomFit = $eventRoomFit;
        $this->timeslotNeighborNext = $timeslotNeighborNext;
        $this->eventGroups = $eventGroups;
        $this->eventTeacher = $eventTeacher;


        $this->matrixFlattener = $matrixFlattener;

        $this->eventRepository = $eventRepository;
        $this->roomRepository = $roomRepository;
        $this->timeslotRepository = $timeslotRepository;
        $this->studentGroupRepository = $studentGroupRepository;
        $this->teacherRepository = $teacherRepository;
        $this->planRepository = $planRepository;
        $this->subjectRepository = $subjectRepository;
    }

    public function generateNormalizedData(Plan $plan) : void
    {
        $calculatorFilePathName = sprintf(
            "%s/%s",
            $this->dataPath,
            $plan->getId()
        );

        touch($calculatorFilePathName);

        $subjects = $this->subjectRepository->findBy(['plan' => $plan], ['id' => 'asc']);
        $events = $this->eventRepository->findByPlanOrderByIdAsc($plan);
        $rooms = $this->roomRepository->findBy(['plan' => $plan], ['id' => 'asc']);
        $timeslots = $this->timeslotRepository->findBy(['plan' => $plan], ['id' => 'asc']);

        file_put_contents($calculatorFilePathName, $this->eventRepository->countByPlan($plan) . "\n\n",FILE_APPEND);
        file_put_contents($calculatorFilePathName, $this->roomRepository->count(['plan' => $plan]) . "\n\n",FILE_APPEND);
        file_put_contents($calculatorFilePathName, $this->timeslotRepository->count(['plan' => $plan]) . "\n\n",FILE_APPEND);
        file_put_contents($calculatorFilePathName, $this->studentGroupRepository->count(['plan' => $plan]) . "\n\n",FILE_APPEND);
        file_put_contents($calculatorFilePathName, $this->teacherRepository->count(['plan' => $plan]) . "\n\n",FILE_APPEND);
        file_put_contents($calculatorFilePathName, count($eventBlockGenerated = $this->eventBlock->generate(...$subjects)) . "\n\n",FILE_APPEND);

        file_put_contents(
            $calculatorFilePathName,
            $this->matrixFlattener->flatten(
                $eventBlockGenerated,
                $this->eventBlock->getMode()
            ) . "\n",
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->matrixFlattener->flatten(
                $this->eventTimeslotShare->generate(...$events),
                $this->eventTimeslotShare->getMode()
            ) . "\n",
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->matrixFlattener->flatten(
                $this->eventRoomFit->generate($events, $rooms),
                $this->eventRoomFit->getMode()
            ) . "\n",
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->matrixFlattener->flatten(
                $this->timeslotNeighborNext->generate(...$timeslots),
                $this->timeslotNeighborNext->getMode()
            ) . "\n",
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->matrixFlattener->flatten(
                $this->eventGroups->generate(...$events),
                $this->eventGroups->getMode()
            ) . "\n",
            FILE_APPEND
        );

        file_put_contents(
            $calculatorFilePathName,
            $this->matrixFlattener->flatten(
                $this->eventTeacher->generate(...$events),
                $this->eventTeacher->getMode()
            ) . "\n",
            FILE_APPEND
        );

    }

}