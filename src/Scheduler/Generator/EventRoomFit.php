<?php

declare(strict_types=1);

namespace App\Scheduler\Generator;

use App\Entity\Event;
use App\Entity\Room;
use App\Scheduler\Condition;
use App\Scheduler\Condition\EventRoomFit\RoomHasRequiredFeatures;
use App\Scheduler\Generator;
use App\Scheduler\TruthMatrixGenerator;

class EventRoomFit implements Generator
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