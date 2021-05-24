<?php

declare(strict_types=1);

namespace App\Scheduler\Normalization\Generator;

use App\Entity\Event;
use App\Scheduler\Normalization\Generator;

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