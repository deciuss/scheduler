<?php

declare(strict_types=1);

namespace App\Tests\Stub\Mother;

use App\Entity\Room;

class RoomMother
{
    public static function create(int $mapId = 0) : Room
    {
        return (new Room())->setMapId($mapId);
    }
}