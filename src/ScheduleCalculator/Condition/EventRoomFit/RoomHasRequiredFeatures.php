<?php

declare(strict_types=1);

namespace App\ScheduleCalculator\Condition\EventRoomFit;

use App\Entity\Event;
use App\Entity\Feature;
use App\Entity\Room;
use App\ScheduleCalculator\Condition;

class RoomHasRequiredFeatures implements Condition
{

    public function check($item1, $item2): bool
    {
        assert($item1 instanceof Event, 'Invalid type');
        assert($item2 instanceof Room, 'Invalid type');
        return array_reduce(
            $item1->getSubject()->getFeatures()->toArray(),
            function(bool $overallCarry, Feature $searchedFeature) use (&$item2) {
                return $overallCarry && array_reduce(
                    $item2->getFeatures()->toArray(),
                    function(bool $carry, Feature $feature) use (&$searchedFeature) {
                        return $carry || $searchedFeature->getId() == $feature->getId();
                    },
                    false
                    );
            },
            true
        );
    }
}