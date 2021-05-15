<?php

declare(strict_types=1);

namespace App\Tests\Unit\ScheduleCalculator\Condition\EventTimeslotShare;

use App\ScheduleCalculator\Condition\EventTimeslotShare\NotSameTeacher;
use App\Tests\Stub\Mother\EventMother;
use App\Tests\Stub\Mother\SubjectMother;
use App\Tests\Stub\Mother\TeacherMother;
use App\Tests\Unit\TestCase;

/**
 * @covers \App\ScheduleCalculator\Condition\EventTimeslotShare\NotSameTeacher
 */
class NotSameTeacherTest extends TestCase
{

    public function test_if_gives_positive_result_when_events_with_different_teachers() : void
    {
        $this->givenSubjectHasEvents(
            $subject1 = SubjectMother::withHoursWithBlockSize(),
            $event1 = EventMother::withMapId(0)
        );

        $this->givenSubjectHasEvents(
            $subject2 = SubjectMother::withHoursWithBlockSize(),
            $event2 = EventMother::withMapId(1)
        );

        $this->givenTeacherHasSubjects(
            TeacherMother::withId(0),
            $subject1
        );

        $this->givenTeacherHasSubjects(
            TeacherMother::withId(1),
            $subject2
        );

        $actualNotSameTeacherValue = (new NotSameTeacher())->check($event1, $event2);

        $this->assertTrue($actualNotSameTeacherValue);
    }

    public function test_if_gives_negative_result_when_events_with_the_same_teacher() : void
    {
        $subjects = [];

        $this->givenSubjectHasEvents(
            $subjects[] = SubjectMother::withHoursWithBlockSize(),
            $event1 = EventMother::withMapId(0)
        );

        $this->givenSubjectHasEvents(
            $subjects[] = SubjectMother::withHoursWithBlockSize(),
            $event2 = EventMother::withMapId(1)
        );

        $this->givenTeacherHasSubjects(
            TeacherMother::withId(0),
            ...$subjects
        );

        $actualNotSameTeacherValue = (new NotSameTeacher())->check($event1, $event2);

        $this->assertFalse($actualNotSameTeacherValue);
    }


}
