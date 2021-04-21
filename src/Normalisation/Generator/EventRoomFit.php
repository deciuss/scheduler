<?php


namespace App\Normalisation\Generator;


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

    public function generate() : array
    {
        return $this->truthMatrixGenerator->generate(
            $this->eventRepository->findAll(),
            $this->roomRepository->findAll(),
            ...$this->conditions
        );
    }

}