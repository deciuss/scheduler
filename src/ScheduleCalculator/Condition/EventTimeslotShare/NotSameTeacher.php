<?php

declare(strict_types=1);

namespace App\ScheduleCalculator\Condition\EventTimeslotShare;

use App\Entity\Event;
use App\ScheduleCalculator\Condition;

class NotSameTeacher implements Condition
{

    public function check($item1, $item2): bool
    {
        assert($item1 instanceof Event, 'Invalid type');
        assert($item2 instanceof Event, 'Invalid type');
        return $item1->getSubject()->getTeacher()->getId() !== $item2->getSubject()->getTeacher()->getId();
    }
}