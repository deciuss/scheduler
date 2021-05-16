<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Condition\EventTimeslotShare;

use PHPUnit\Framework\TestCase;
use App\Scheduler\Condition\EventTimeslotShare\NotIntersectingStudentGroup;
use App\Tests\Fake\Mother\EventMother;
use App\Tests\Fake\Mother\StudentGroupMother;
use App\Tests\Fake\Mother\SubjectMother;

/**
 * @covers \App\Scheduler\Condition\EventTimeslotShare\NotIntersectingStudentGroup
 */
class NotIntersectingStudentGroupTest extends TestCase
{
    public function test_if_gives_positive_result_when_events_for_groups_that_do_not_intersect() : void
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

        $actualNotIntersectingStudentGroupValue = (new NotIntersectingStudentGroup())->check($event1, $event2);

        $this->assertTrue($actualNotIntersectingStudentGroupValue);
    }

    public function test_if_gives_negative_result_when_events_for_groups_that_do_intersect() : void
    {
        $event1 = EventMother::withSubject(
            SubjectMother::withStudentGroup(
                $studentGroup0 = StudentGroupMother::withId(0)
            )
        );

        $event2 = EventMother::withSubject(
            SubjectMother::withStudentGroup(
                $studentGroup1 = StudentGroupMother::withId(1)
            )
        );

        $studentGroup0->addStudentGroupsIntersected($studentGroup1);
        $studentGroup1->addStudentGroupsIntersected($studentGroup0);

        $actualNotIntersectingStudentGroupValue = (new NotIntersectingStudentGroup())->check($event1, $event2);

        $this->assertFalse($actualNotIntersectingStudentGroupValue);
    }

    public function test_if_gives_positive_result_when_events_for_groups_that_do_not_intersect_with_each_other_but_have_other_intersections() : void
    {
        $event1 = EventMother::withSubject(
            SubjectMother::withStudentGroup(
                StudentGroupMother::withId(0)
            )
        );

        $event2 = EventMother::withSubject(
            SubjectMother::withStudentGroup(
                $studentGroup1 = StudentGroupMother::withId(1)
            )
        );

        $studentGroup2 = StudentGroupMother::withId(2);

        $studentGroup1->addStudentGroupsIntersected($studentGroup2);
        $studentGroup2->addStudentGroupsIntersected($studentGroup1);

        $actualNotIntersectingStudentGroupValue = (new NotIntersectingStudentGroup())->check($event1, $event2);

        $this->assertTrue($actualNotIntersectingStudentGroupValue);
    }

}