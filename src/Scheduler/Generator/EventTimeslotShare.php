<?php

declare(strict_types=1);

namespace App\Scheduler\Generator;

use App\Entity\Event;
use App\Scheduler\Condition;
use App\Scheduler\Condition\EventTimeslotShare\NotIntersectingStudentGroup;
use App\Scheduler\Condition\EventTimeslotShare\NotSameStudentGroup;
use App\Scheduler\Condition\EventTimeslotShare\NotSameTeacher;
use App\Scheduler\Generator;
use App\Scheduler\TruthMatrixGenerator;

class EventTimeslotShare implements Generator
{
    private TruthMatrixGenerator $truthMatrixGenerator;

    /**
     * @var Condition[]
     */
    private array $conditions;

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