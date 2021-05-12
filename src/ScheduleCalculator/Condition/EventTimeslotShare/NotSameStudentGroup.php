<?php


namespace App\ScheduleCalculator\Condition\EventTimeslotShare;


use App\Entity\Event;
use App\ScheduleCalculator\Condition;

class NotSameStudentGroup implements Condition
{

    public function check($item1, $item2): bool
    {
        assert($item1 instanceof Event, 'Invalid type');
        assert($item2 instanceof Event, 'Invalid type');
        return $item1->getSubject()->getStudentGroup()->getId() !== $item2->getSubject()->getStudentGroup()->getId();
    }
}