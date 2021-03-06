<?php

declare(strict_types=1);

namespace App\Tests\Fake\Mother;

use App\Entity\Feature;
use App\Entity\Room;

class RoomMother
{
    public static function withFeatures(Feature ...$features) : Room
    {
        return array_reduce(
            $features,
            fn(Room $room, Feature $feature) => $room->addFeature($feature),
            new Room()
        );
    }

    public static function withCapacity(int $capacity) : Room
    {
        return (new Room())->setCapacity($capacity);
    }
}