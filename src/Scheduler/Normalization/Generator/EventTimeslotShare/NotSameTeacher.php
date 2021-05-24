<?php

declare(strict_types=1);

namespace App\Scheduler\Normalization\Generator\EventTimeslotShare;

use App\Entity\Event;
use App\Scheduler\Normalization\Condition;

class NotSameTeacher implements Condition
{
 public function check($event1, $event2): bool
    {
        assert($event1 instanceof Event, 'Invalid type');
        assert($event2 instanceof Event, 'Invalid type');
        return $event1->getSubject()->getTeacher()->getId() !== $event2->getSubject()->getTeacher()->getId();
    }
}