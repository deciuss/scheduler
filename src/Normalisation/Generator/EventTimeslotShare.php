<?php


namespace App\Normalisation\Generator;


use App\Entity\Plan;
use App\Normalisation\Condition;
use App\Normalisation\Condition\EventTimeslotShare\NotIntersectingStudentGroup;
use App\Normalisation\Condition\EventTimeslotShare\NotSameStudentGroup;
use App\Normalisation\Condition\EventTimeslotShare\NotSameTeacher;
use App\Normalisation\Generator;
use App\Normalisation\TruthMatrixGenerator;
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