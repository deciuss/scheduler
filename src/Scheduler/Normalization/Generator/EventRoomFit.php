<?php

declare(strict_types=1);

namespace App\Scheduler\Normalization\Generator;

use App\Entity\Event;
use App\Entity\Room;
use App\Scheduler\Normalization\Generator;
use App\Scheduler\Normalization\Generator\EventRoomFit\RoomHasRequiredCapacity;
use App\Scheduler\Normalization\Generator\EventRoomFit\RoomHasRequiredFeatures;
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
        RoomHasRequiredFeatures $roomHasRequiredFeatures,
        RoomHasRequiredCapacity $roomHasRequiredCapacity
    ) {
        $this->truthMatrixGenerator = $truthMatrixGenerator;
        $this->conditions[] = $roomHasRequiredFeatures;
        $this->conditions[] = $roomHasRequiredCapacity;
    }

    /**
     * @param Event[] $events
     * @param Room[]  $rooms
     */
    public function generate(array $events, array $rooms): array
    {
        return $this->truthMatrixGenerator->generate($events, $rooms, ...$this->conditions);
    }
}
