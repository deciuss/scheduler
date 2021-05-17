<?php

declare(strict_types=1);

namespace App\Scheduler\Generator;

use App\Entity\Event;
use App\Scheduler\Generator;

class EventGroups implements Generator
{
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