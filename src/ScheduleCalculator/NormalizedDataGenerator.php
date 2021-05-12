<?php


namespace App\ScheduleCalculator;


use App\Entity\Plan;
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
    private EventBlock $eventBlock;

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
        PlanRepository $planRepository
    ) {
        $this->dataPath = $parameterBag->get('scheduler.calculator.data_path');

        $this->generators[] = $eventBlock;
        $this->generators[] = $eventTimeslotShare;
        $this->generators[] = $eventRoomFit;
        $this->generators[] = $timeslotNeighborNext;
        $this->generators[] = $eventGroups;
        $this->generators[] = $eventTeacher;

        $this->eventBlock = $eventBlock;

        $this->matrixFlattener = $matrixFlattener;

        $this->eventRepository = $eventRepository;
        $this->roomRepository = $roomRepository;
        $this->timeslotRepository = $timeslotRepository;
        $this->studentGroupRepository = $studentGroupRepository;
        $this->teacherRepository = $teacherRepository;
        $this->planRepository = $planRepository;
    }

    public function generateNormalizedData(Plan $plan) : void
    {
        $calculatorFilePathName = sprintf(
            "%s/%s",
            $this->dataPath,
            $plan->getId()
        );

        touch($calculatorFilePathName);

        file_put_contents($calculatorFilePathName, $this->eventRepository->countByPlan($plan) . "\n\n",FILE_APPEND);
        file_put_contents($calculatorFilePathName, $this->roomRepository->count(['plan' => $plan]) . "\n\n",FILE_APPEND);
        file_put_contents($calculatorFilePathName, $this->timeslotRepository->count(['plan' => $plan]) . "\n\n",FILE_APPEND);
        file_put_contents($calculatorFilePathName, $this->studentGroupRepository->count(['plan' => $plan]) . "\n\n",FILE_APPEND);
        file_put_contents($calculatorFilePathName, $this->teacherRepository->count(['plan' => $plan]) . "\n\n",FILE_APPEND);
        file_put_contents($calculatorFilePathName, count($this->eventBlock->generate($plan)) . "\n\n",FILE_APPEND);

        foreach ($this->generators as $generator) {
            file_put_contents(
                $calculatorFilePathName,
                $this->matrixFlattener->flatten($generator->generate($plan), $generator->getMode()) . "\n",
                FILE_APPEND
            );
        }
    }

}