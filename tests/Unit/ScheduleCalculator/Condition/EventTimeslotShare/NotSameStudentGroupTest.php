<?php

declare(strict_types=1);

namespace App\Tests\Unit\ScheduleCalculator\Condition\EventTimeslotShare;

use App\ScheduleCalculator\Condition\EventTimeslotShare\NotSameStudentGroup;
use App\Tests\Stub\Mother\EventMother;
use App\Tests\Stub\Mother\StudentGroupMother;
use App\Tests\Stub\Mother\SubjectMother;
use App\Tests\Unit\TestCase;

/**
 * @covers \App\ScheduleCalculator\Condition\EventTimeslotShare\NotSameStudentGroup
 */
class NotSameStudentGroupTest extends TestCase
{

    public function test_if_gives_positive_result_when_events_not_for_same_group() : void
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

        $actualNotSameStudentGroupValue = (new NotSameStudentGroup())->check($event1, $event2);

        $this->assertTrue($actualNotSameStudentGroupValue);
    }

    public function test_if_gives_negative_result_when_events_for_the_same_group() : void
    {
        $this->givenStudentGroupHasSubjects(
            $group1 = StudentGroupMother::create(0),
            $subject1 = SubjectMother::create()
        );

        $this->givenStudentGroupHasSubjects(
            $group1,
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

        $actualNotSameStudentGroupValue = (new NotSameStudentGroup())->check($event1, $event2);

        $this->assertFalse($actualNotSameStudentGroupValue);
    }

}
