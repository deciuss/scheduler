<?php

declare(strict_types=1);

namespace App\Tests\Unit\ScheduleCalculator\Condition\EventTimeslotShare;

use App\ScheduleCalculator\Condition\EventTimeslotShare\NotIntersectingStudentGroup;
use App\Tests\Stub\Mother\EventMother;
use App\Tests\Stub\Mother\StudentGroupMother;
use App\Tests\Stub\Mother\SubjectMother;
use App\Tests\Unit\TestCase;

/**
 * @covers \App\ScheduleCalculator\Condition\EventTimeslotShare\NotIntersectingStudentGroup
 */
class NotIntersectingStudentGroupTest extends TestCase
{

    public function test_if_gives_positive_result_when_events_for_groups_that_do_not_intersect() : void
    {
        $this->givenStudentGroupHasSubjects(
            StudentGroupMother::create(0),
            $subject1 = SubjectMother::create()
        );

        $this->givenStudentGroupHasSubjects(
            StudentGroupMother::create(1),
            $subject2 = SubjectMother::create()
        );

        $this->givenSubjectHasEvents(
            $subject1,
            $event1 = EventMother::create(0)
        );

        $this->givenSubjectHasEvents(
            $subject2,
            $event2 = EventMother::create(1)
        );

        $actualNotIntersectingStudentGroupValue = (new NotIntersectingStudentGroup())->check($event1, $event2);

        $this->assertTrue($actualNotIntersectingStudentGroupValue);
    }

    public function test_if_gives_negative_result_when_events_for_groups_that_do_intersect() : void
    {
        $this->givenStudentGroupsAreIntersecting(
            $group1 = StudentGroupMother::create(0),
            $group2 = StudentGroupMother::create(1)
        );

        $this->givenStudentGroupHasSubjects(
            $group1,
            $subject1 = SubjectMother::create()
        );

        $this->givenStudentGroupHasSubjects(
            $group2,
            $subject2 = SubjectMother::create()
        );

        $this->givenSubjectHasEvents(
            $subject1,
            $event1 = EventMother::create(0)
        );

        $this->givenSubjectHasEvents(
            $subject2,
            $event2 = EventMother::create(1)
        );

        $actualNotIntersectingStudentGroupValue = (new NotIntersectingStudentGroup())->check($event1, $event2);

        $this->assertFalse($actualNotIntersectingStudentGroupValue);
    }

    public function test_if_gives_positive_result_when_events_for_groups_that_do_not_intersect_with_each_other_but_have_other_intersections() : void
    {
        $this->givenStudentGroupsAreIntersecting(
            $group1 = StudentGroupMother::create(0),
            StudentGroupMother::create(1)
        );

        $this->givenStudentGroupsAreIntersecting(
            $group2 = StudentGroupMother::create(2),
            StudentGroupMother::create(3)
        );


        $this->givenStudentGroupHasSubjects(
            $group1,
            $subject1 = SubjectMother::create()
        );

        $this->givenStudentGroupHasSubjects(
            $group2,
            $subject2 = SubjectMother::create()
        );

        $this->givenSubjectHasEvents(
            $subject1,
            $event1 = EventMother::create(0)
        );

        $this->givenSubjectHasEvents(
            $subject2,
            $event2 = EventMother::create(1)
        );

        $actualNotIntersectingStudentGroupValue = (new NotIntersectingStudentGroup())->check($event1, $event2);

        $this->assertTrue($actualNotIntersectingStudentGroupValue);
    }

}