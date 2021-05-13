<?php

declare(strict_types=1);

namespace App\ScheduleCalculator\Generator;

use App\Entity\Event;
use App\ScheduleCalculator\Generator;

class EventGroups implements Generator
{
    public function getMode() : string
    {
        return 'intOneToMany';
    }

    public function generate(Event ...$events) : array
    {
        $eventGroups = [];
        foreach ($events as $event) {
            $group = $event->getSubject()->getStudentGroup();
            $eventGroups[$event->getMapId()][] = $group->getMapId();
            foreach($group->getChildren() as $child) {
                $eventGroups[$event->getMapId()][] = $child->getMapId();
            }
        }
        return $eventGroups;
    }

}