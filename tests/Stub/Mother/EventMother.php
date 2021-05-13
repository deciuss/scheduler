<?php

declare(strict_types=1);

namespace App\Tests\Stub\Mother;

use App\Entity\Event;

class EventMother
{
    public static function create(int $mapId = 0) : Event
    {
        return (new Event())
            ->setMapId($mapId);
    }
}