<?php

declare(strict_types=1);

namespace App\Scheduler\Normalization\Generator;

use App\Entity\Event;
use App\Scheduler\Normalization\Condition;
use App\Scheduler\Normalization\Generator;
use App\Scheduler\Normalization\Generator\EventTimeslotShare\NotIntersectingStudentGroup;
use App\Scheduler\Normalization\Generator\EventTimeslotShare\NotSameStudentGroup;
use App\Scheduler\Normalization\Generator\EventTimeslotShare\NotSameTeacher;
use App\Scheduler\Normalization\TruthMatrixGenerator;

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
    ) {
        $this->truthMatrixGenerator = $truthMatrixGenerator;
        $this->conditions[] = $notIntersectingStudentGroup;
        $this->conditions[] = $notSameStudentGroup;
        $this->conditions[] = $notSameTeacher;
    }

    public function generate(Event ...$events): array
    {
        return $this->truthMatrixGenerator->generate($events, $events, ...$this->conditions);
    }
}
