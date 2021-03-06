<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Normalization\Generator\EventTimeslotShare;

use PHPUnit\Framework\TestCase;
use App\Scheduler\Normalization\Generator\EventTimeslotShare\NotSameTeacher;
use App\Tests\Fake\Mother\EventMother;
use App\Tests\Fake\Mother\SubjectMother;
use App\Tests\Fake\Mother\TeacherMother;

/**
 * @covers \App\Scheduler\Normalization\Generator\EventTimeslotShare\NotSameTeacher
 */
class NotSameTeacherTest extends TestCase
{
    public function test_gives_positive_result_when_events_with_different_teachers() : void
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

    public function test_gives_negative_result_when_events_with_the_same_teacher() : void
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
