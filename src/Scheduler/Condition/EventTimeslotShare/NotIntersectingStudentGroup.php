<?php

declare(strict_types=1);

namespace App\Scheduler\Condition\EventTimeslotShare;

use App\Entity\Event;
use App\Entity\StudentGroup;
use App\Scheduler\Condition;

class NotIntersectingStudentGroup implements Condition
{
 public function check($item1, $item2): bool
    {
        assert($item1 instanceof Event, 'Invalid type');
        assert($item2 instanceof Event, 'Invalid type');
        return array_reduce(
            $item2->getSubject()->getStudentGroup()->getStudentGroupsIntersected()->toArray(),
            function(bool $carry, StudentGroup $studentGroup) use (&$item1) {
                return $carry && $studentGroup->getId() != $item1->getSubject()->getStudentGroup()->getId();
            },
            true
        );
    }
}