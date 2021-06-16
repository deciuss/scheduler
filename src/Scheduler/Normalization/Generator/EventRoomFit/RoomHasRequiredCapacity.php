<?php

declare(strict_types=1);

namespace App\Scheduler\Normalization\Generator\EventRoomFit;

use App\Entity\Event;
use App\Entity\Room;
use App\Scheduler\Normalization\Condition;

class RoomHasRequiredCapacity implements Condition
{
    public function check($event, $room): bool
    {
        assert($event instanceof Event, 'Invalid type');
        assert($room instanceof Room, 'Invalid type');

        return $room->getCapacity() >= $event->getSubject()->getStudentGroup()->getCardinality();
    }
}
