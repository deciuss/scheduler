<?php


namespace App\Normalisation\Generator;


use App\Normalisation\Condition;
use App\Normalisation\Condition\EventSameSubject\IsOfTheSameSubject;
use App\Normalisation\Generator;
use App\Normalisation\TruthMatrixGenerator;
use App\Repository\EventRepository;

class EventSameSubject implements Generator
{

    private TruthMatrixGenerator $truthMatrixGenerator;
    private EventRepository $eventRepository;

    /**
     * @var Condition[]
     */
    private array $conditions;

    public function __construct(
        TruthMatrixGenerator $truthMatrixGenerator,
        EventRepository $eventRepository,
        IsOfTheSameSubject $isOfTheSameSubject
    ){
        $this->truthMatrixGenerator = $truthMatrixGenerator;
        $this->eventRepository = $eventRepository;
        $this->conditions[] = $isOfTheSameSubject;
    }

    public function generate() : array
    {
        $events = $this->eventRepository->findAll();
        return $this->truthMatrixGenerator->generate($events, $events, ...$this->conditions);
    }

}