<?php

declare(strict_types=1);

namespace App\ScheduleCalculator\Generator;

use App\Entity\Event;
use App\ScheduleCalculator\Generator;

class EventTeacher implements Generator
{
    public function getMode() : string
    {
        return 'intArray';
    }

    public function generate(Event ...$events) : array
    {
        $eventTeacher = [];
        foreach ($events as $event) {
            $eventTeacher[$event->getMapId()] = $event->getSubject()->getTeacher()->getMapId();
        }
        return $eventTeacher;
    }

}