<?php


namespace App\Normalisation\Generator;


use App\Entity\Plan;
use App\Normalisation\Condition;
use App\Normalisation\Condition\EventRoomFit\RoomHasRequiredFeatures;
use App\Normalisation\Generator;
use App\Normalisation\TruthMatrixGenerator;
use App\Repository\EventRepository;
use App\Repository\RoomRepository;

class EventRoomFit implements Generator
{

    private TruthMatrixGenerator $truthMatrixGenerator;
    private EventRepository $eventRepository;
    private RoomRepository $roomRepository;

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
        RoomRepository $roomRepository,
        RoomHasRequiredFeatures $roomHasRequiredFeatures
    ){
        $this->truthMatrixGenerator = $truthMatrixGenerator;
        $this->eventRepository = $eventRepository;
        $this->roomRepository = $roomRepository;
        $this->conditions[] = $roomHasRequiredFeatures;
    }

    public function generate(Plan $plan) : array
    {
        return $this->truthMatrixGenerator->generate(
            $this->eventRepository->findByPlanOrderByIdAsc($plan),
            $this->roomRepository->findBy(['plan' => $plan], ['id' => 'asc']),
            ...$this->conditions
        );
    }

}