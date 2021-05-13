<?php

declare(strict_types=1);

namespace App\Tests\Unit\ScheduleCalculator\Generator;

use App\ScheduleCalculator\Generator\EventTeacher;
use App\Tests\Stub\Mother\EventMother;
use App\Tests\Stub\Mother\SubjectMother;
use App\Tests\Stub\Mother\TeacherMother;
use App\Tests\Unit\TestCase;

/**
 * @covers \App\ScheduleCalculator\Generator\EventTeacher
 */
class EventTeacherTest extends TestCase
{

    public function test_if_generates_empty_output_when_no_data_present() : void
    {
        $events = [];

        $actualEventTeacherArray = (new EventTeacher())->generate(...$events);

        $this->assertEquals([], $actualEventTeacherArray);
    }

    public function test_if_assigninh_teachers_when_multiple_teachers_present() : void
    {
        $events = [];

        $this->givenSubjectHasEvents(
            $lecture = SubjectMother::create(5, 2),
            $events[] = EventMother::create(0),
            $events[] = EventMother::create(1),
            $events[] = EventMother::create(2),
            $events[] = EventMother::create(3),
            $events[] = EventMother::create(4)
        );

        $this->givenSubjectHasEvents(
            $laboratory = SubjectMother::create(1, 1),
            $events[] = EventMother::create(5)
        );

        $this->givenSubjectHasEvents(
            $exercises = SubjectMother::create(2, 1),
            $events[] = EventMother::create(6),
            $events[] = EventMother::create(7)
        );

        $this->givenSubjectHasEvents(
            $seminary = SubjectMother::create(2, 1),
            $events[] = EventMother::create(8),
            $events[] = EventMother::create(9)
        );

        $this->givenTeacherHasSubjects(
            TeacherMother::create(0),
            $lecture
        );

        $this->givenTeacherHasSubjects(
            TeacherMother::create(1),
            $laboratory,
            $seminary
        );

        $this->givenTeacherHasSubjects(
            TeacherMother::create(2),
            $exercises
        );

        $actualEventTeacherArray = (new EventTeacher())->generate(...$events);

        $this->assertEquals(
            [0, 0, 0, 0, 0, 1, 2, 2, 1, 1],
            $actualEventTeacherArray
        );
    }
}
