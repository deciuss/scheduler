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
            $lecture = SubjectMother::withHoursWithBlockSize(5, 2),
            $events[] = EventMother::withMapId(0),
            $events[] = EventMother::withMapId(1),
            $events[] = EventMother::withMapId(2),
            $events[] = EventMother::withMapId(3),
            $events[] = EventMother::withMapId(4)
        );

        $this->givenSubjectHasEvents(
            $laboratory = SubjectMother::withHoursWithBlockSize(1, 1),
            $events[] = EventMother::withMapId(5)
        );

        $this->givenSubjectHasEvents(
            $exercises = SubjectMother::withHoursWithBlockSize(2, 1),
            $events[] = EventMother::withMapId(6),
            $events[] = EventMother::withMapId(7)
        );

        $this->givenSubjectHasEvents(
            $seminary = SubjectMother::withHoursWithBlockSize(2, 1),
            $events[] = EventMother::withMapId(8),
            $events[] = EventMother::withMapId(9)
        );

        $this->givenTeacherHasSubjects(
            TeacherMother::withMapId(0),
            $lecture
        );

        $this->givenTeacherHasSubjects(
            TeacherMother::withMapId(1),
            $laboratory,
            $seminary
        );

        $this->givenTeacherHasSubjects(
            TeacherMother::withMapId(2),
            $exercises
        );

        $actualEventTeacherArray = (new EventTeacher())->generate(...$events);

        $this->assertEquals(
            [0, 0, 0, 0, 0, 1, 2, 2, 1, 1],
            $actualEventTeacherArray
        );
    }
}
