<?php

declare(strict_types=1);

namespace App\Tests\Stub\Mother;

use App\Entity\Timeslot;

class TimeslotMother
{
    public static function create(int $mapId, \DateTime $start, \DateTime $end) : Timeslot
    {
        return (new Timeslot())->setMapId($mapId)->setStart($start)->setEnd($end);
    }
}