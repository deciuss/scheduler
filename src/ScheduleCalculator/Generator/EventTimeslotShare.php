<?php


namespace App\ScheduleCalculator\Generator;


use App\Entity\Plan;
use App\ScheduleCalculator\Condition;
use App\ScheduleCalculator\Condition\EventTimeslotShare\NotIntersectingStudentGroup;
use App\ScheduleCalculator\Condition\EventTimeslotShare\NotSameStudentGroup;
use App\ScheduleCalculator\Condition\EventTimeslotShare\NotSameTeacher;
use App\ScheduleCalculator\Generator;
use App\ScheduleCalculator\TruthMatrixGenerator;
use App\Repository\EventRepository;

class EventTimeslotShare implements Generator
{

    private TruthMatrixGenerator $truthMatrixGenerator;
    private EventRepository $eventRepository;

    /**
     * @var Condition[]
     */
    private array $conditions;

    public function getMode() : string
    {
        return 'string';
    }

    public function __construct(
        TruthMatrixGenerator $truthMatrixGenerator,
        EventRepository $eventRepository,
        NotIntersectingStudentGroup $notIntersectingStudentGroup,
        NotSameStudentGroup $notSameStudentGroup,
        NotSameTeacher $notSameTeacher
    ){
        $this->truthMatrixGenerator = $truthMatrixGenerator;
        $this->eventRepository = $eventRepository;
        $this->conditions[] = $notIntersectingStudentGroup;
        $this->conditions[] = $notSameStudentGroup;
        $this->conditions[] = $notSameTeacher;
    }

    public function generate(Plan $plan) : array
    {
        $events = $this->eventRepository->findByPlanOrderByIdAsc($plan);
        return $this->truthMatrixGenerator->generate($events, $events, ...$this->conditions);
    }

}