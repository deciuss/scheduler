<?php

declare(strict_types=1);

namespace App\ScheduleCalculator\Generator;

use App\Entity\Event;
use App\Entity\Plan;
use App\Entity\Room;
use App\ScheduleCalculator\Condition;
use App\ScheduleCalculator\Condition\EventRoomFit\RoomHasRequiredFeatures;
use App\ScheduleCalculator\Generator;
use App\ScheduleCalculator\TruthMatrixGenerator;
use App\Repository\EventRepository;
use App\Repository\RoomRepository;

class EventRoomFit implements Generator
{
    private TruthMatrixGenerator $truthMatrixGenerator;

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