<?php

declare(strict_types=1);

namespace App\Tests\Fake\Mother;

use App\Entity\Event;
use App\Entity\Subject;

class EventMother
{
    public static function withMapId(int $mapId) : Event
    {
        return (new Event())
            ->setMapId($mapId);
    }

    public static function withSubject(Subject $subject) : Event
    {
        return (new Event())
            ->setSubject($subject);
    }
}