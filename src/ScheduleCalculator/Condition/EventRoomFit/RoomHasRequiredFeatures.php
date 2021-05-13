<?php

declare(strict_types=1);

namespace App\ScheduleCalculator\Condition\EventRoomFit;

use App\Entity\Feature;
use App\ScheduleCalculator\CalculatorMapping;
use App\ScheduleCalculator\Condition;

class RoomHasRequiredFeatures implements Condition
{

    public function check(CalculatorMapping $event, CalculatorMapping $room): bool
    {
        return array_reduce(
            $event->getSubject()->getFeatures()->toArray(),
            function(bool $overallCarry, Feature $searchedFeature) use (&$room) {
                return $overallCarry && array_reduce(
                    $room->getFeatures()->toArray(),
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