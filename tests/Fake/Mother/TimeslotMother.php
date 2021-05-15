<?php

declare(strict_types=1);

namespace App\Tests\Fake\Mother;

use App\Entity\Timeslot;

class TimeslotMother
{
    public static function withMapId(int $mapId) : Timeslot
    {
        return (new Timeslot())->setMapId($mapId);
    }
}