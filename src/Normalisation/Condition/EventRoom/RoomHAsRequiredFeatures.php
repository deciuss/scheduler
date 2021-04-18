<?php


namespace App\Normalisation\Condition\EventRoom;


use App\Entity\Event;
use App\Entity\Room;
use App\Normalisation\Condition;

class RoomHAsRequiredFeatures implements Condition
{

    public function check($item1, $item2): bool
    {
        assert($item1 instanceof Event, 'Invalid type');
        assert($item2 instanceof Room, 'Invalid type');
        return $item1->getSubject()->getFeatures() !== $item2->getSubject()->getTeacher()->getId();
    }
}