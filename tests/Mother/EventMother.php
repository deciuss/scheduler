<?php

declare(strict_types=1);

namespace App\Tests\Mother;

use App\Entity\Event;
use App\Entity\Subject;

class EventMother
{
    public static function create(int $mapId) : Event
    {
        return (new Event())
            ->setMapId($mapId);
    }
}