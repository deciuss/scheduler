<?php

declare(strict_types=1);

namespace App\Scheduler\Normalization\Generator\EventRoomFit;

use App\Entity\Event;
use App\Entity\Feature;
use App\Entity\Room;
use App\Scheduler\Normalization\Condition;

class RoomHasRequiredFeatures implements Condition
{
    public function check($event, $room): bool
    {
        assert($event instanceof Event, 'Invalid type');
        assert($room instanceof Room, 'Invalid type');

        return array_reduce(
            $event->getSubject()->getFeatures()->toArray(),
            function (bool $overallCarry, Feature $searchedFeature) use (&$room) {
                return $overallCarry && array_reduce(
                    $room->getFeatures()->toArray(),
                    function (bool $carry, Feature $feature) use (&$searchedFeature) {
                        return $carry || $searchedFeature->getId() == $feature->getId();
                    },
                    false
                    );
            },
            true
        );
    }
}
