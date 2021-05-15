<?php

declare(strict_types=1);

namespace App\Tests\Unit\ScheduleCalculator\Condition\EventTimeslotShare;

use PHPUnit\Framework\TestCase;
use App\ScheduleCalculator\Condition\EventTimeslotShare\NotSameTeacher;
use App\Tests\Stub\Mother\EventMother;
use App\Tests\Stub\Mother\SubjectMother;
use App\Tests\Stub\Mother\TeacherMother;

/**
 * @covers \App\ScheduleCalculator\Condition\EventTimeslotShare\NotSameTeacher
 */
class NotSameTeacherTest extends TestCase
{

    public function test_if_gives_positive_result_when_events_with_different_teachers() : void
    {
        $event1 = EventMother::withSubject(
            SubjectMother::withTeacher(
                TeacherMother::withId(0)
            ),
        );

        $event2 = EventMother::withSubject(
            SubjectMother::withTeacher(
                TeacherMother::withId(1)
            ),
        );

        $actualNotSameTeacherValue = (new NotSameTeacher())->check($event1, $event2);

        $this->assertTrue($actualNotSameTeacherValue);
    }

    public function test_if_gives_negative_result_when_events_with_the_same_teacher() : void
    {

        $event1 = EventMother::withSubject(
            SubjectMother::withTeacher(
                $teacher = TeacherMother::withId(0)
            ),
        );

        $event2 = EventMother::withSubject(
            SubjectMother::withTeacher($teacher),
        );

        $actualNotSameTeacherValue = (new NotSameTeacher())->check($event1, $event2);

        $this->assertFalse($actualNotSameTeacherValue);
    }

}
