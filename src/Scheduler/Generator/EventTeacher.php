<?php

declare(strict_types=1);

namespace App\Scheduler\Generator;

use App\Entity\Event;
use App\Scheduler\Generator;

class EventTeacher implements Generator
{
    public function generate(Event ...$events) : array
    {
        $eventTeacher = [];
        foreach ($events as $event) {
            $eventTeacher[$event->getMapId()] = $event->getSubject()->getTeacher()->getMapId();
        }
        return $eventTeacher;
    }

}