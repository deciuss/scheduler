<?php

declare(strict_types=1);

namespace App\ScheduleCalculator\Generator;

use App\Entity\Event;
use App\ScheduleCalculator\Condition;
use App\ScheduleCalculator\Condition\EventTimeslotShare\NotIntersectingStudentGroup;
use App\ScheduleCalculator\Condition\EventTimeslotShare\NotSameStudentGroup;
use App\ScheduleCalculator\Condition\EventTimeslotShare\NotSameTeacher;
use App\ScheduleCalculator\Generator;
use App\ScheduleCalculator\TruthMatrixGenerator;

class EventTimeslotShare implements Generator
{
    private TruthMatrixGenerator $truthMatrixGenerator;

    /**
     * @var Condition[]
     */
    private array $conditions;

    public function getMode() : string
    {
        return 'boolMatrix';
    }

    public function __construct(
        TruthMatrixGenerator $truthMatrixGenerator,
        NotIntersectingStudentGroup $notIntersectingStudentGroup,
        NotSameStudentGroup $notSameStudentGroup,
        NotSameTeacher $notSameTeacher
    ){
        $this->truthMatrixGenerator = $truthMatrixGenerator;
        $this->conditions[] = $notIntersectingStudentGroup;
        $this->conditions[] = $notSameStudentGroup;
        $this->conditions[] = $notSameTeacher;
    }

    public function generate(Event ...$events) : array
    {
        return $this->truthMatrixGenerator->generate($events, $events, ...$this->conditions);
    }

}