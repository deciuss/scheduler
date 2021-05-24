<?php

declare(strict_types=1);

namespace App\Scheduler\Normalization\Generator;

use App\Entity\Event;
use App\Entity\Room;
use App\Scheduler\Normalization\Condition;
use App\Scheduler\Normalization\Generator\EventRoomFit\RoomHasRequiredFeatures;
use App\Scheduler\Normalization\Generator;
use App\Scheduler\Normalization\TruthMatrixGenerator;

class EventRoomFit implements Generator
{
    private TruthMatrixGenerator $truthMatrixGenerator;

    /**
     * @var \App\Scheduler\Normalization\Condition[]
     */
    private array $conditions;

    public function __construct(
        TruthMatrixGenerator $truthMatrixGenerator,
        RoomHasRequiredFeatures $roomHasRequiredFeatures
    ){
        $this->truthMatrixGenerator = $truthMatrixGenerator;
        $this->conditions[] = $roomHasRequiredFeatures;
    }

    /**
     * @param Event[] $events
     * @param Room[] $rooms
     * @return array
     */
    public function generate(array $events, array $rooms) : array
    {
        return $this->truthMatrixGenerator->generate($events, $rooms, ...$this->conditions);
    }

}