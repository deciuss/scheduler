<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Condition\EventTimeslotShare;

use PHPUnit\Framework\TestCase;
use App\Scheduler\Condition\EventTimeslotShare\NotSameStudentGroup;
use App\Tests\Fake\Mother\EventMother;
use App\Tests\Fake\Mother\StudentGroupMother;
use App\Tests\Fake\Mother\SubjectMother;

/**
 * @covers \App\Scheduler\Condition\EventTimeslotShare\NotSameStudentGroup
 */
class NotSameStudentGroupTest extends TestCase
{
    public function test_if_gives_positive_result_when_events_not_for_same_group() : void
    {
        $event1 = EventMother::withSubject(
            SubjectMother::withStudentGroup(
                StudentGroupMother::withId(0)
            )
        );

        $event2 = EventMother::withSubject(
            SubjectMother::withStudentGroup(
                StudentGroupMother::withId(1)
            )
        );

        $actualNotSameStudentGroupValue = (new NotSameStudentGroup())->check($event1, $event2);

        $this->assertTrue($actualNotSameStudentGroupValue);
    }

    public function test_if_gives_negative_result_when_events_for_the_same_group() : void
    {
        $event1 = EventMother::withSubject(
            SubjectMother::withStudentGroup(
                $studentGroup0 =  StudentGroupMother::withId(0)
            )
        );

        $event2 = EventMother::withSubject(
            SubjectMother::withStudentGroup($studentGroup0)
        );

        $actualNotSameStudentGroupValue = (new NotSameStudentGroup())->check($event1, $event2);

        $this->assertFalse($actualNotSameStudentGroupValue);
    }

}
