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
            $subject1 = SubjectMother::create(),
            $event1 = EventMother::create(0)
        );

        $this->givenSubjectHasEvents(
            $subject2 = SubjectMother::create(),
            $event2 = EventMother::create(1)
        );

        $this->givenTeacherHasSubjects(
            TeacherMother::create(0),
            $subject1
        );

        $this->givenTeacherHasSubjects(
            TeacherMother::create(1),
            $subject2
        );

        $actualNotSameTeacherValue = (new NotSameTeacher())->check($event1, $event2);

        $this->assertTrue($actualNotSameTeacherValue);
    }

    public function test_if_gives_negative_result_when_events_with_the_same_teacher() : void
    {
        $subjects = [];

        $this->givenSubjectHasEvents(
            $subjects[] = SubjectMother::create(),
            $event1 = EventMother::create(0)
        );

        $this->givenSubjectHasEvents(
            $subjects[] = SubjectMother::create(),
            $event2 = EventMother::create(1)
        );

        $this->givenTeacherHasSubjects(
            TeacherMother::create(),
            ...$subjects
        );

        $actualNotSameTeacherValue = (new NotSameTeacher())->check($event1, $event2);

        $this->assertFalse($actualNotSameTeacherValue);
    }


}
