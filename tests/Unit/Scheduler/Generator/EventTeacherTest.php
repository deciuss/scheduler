<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Generator;

use PHPUnit\Framework\TestCase;
use App\Scheduler\Generator\EventTeacher;
use App\Tests\Fake\Mother\EventMother;
use App\Tests\Fake\Mother\SubjectMother;
use App\Tests\Fake\Mother\TeacherMother;

/**
 * @covers \App\Scheduler\Generator\EventTeacher
 */
class EventTeacherTest extends TestCase
{

    public function test_if_generates_empty_output_when_no_data_present() : void
    {
        $events = [];

        $actualEventTeacherArray = (new EventTeacher())->generate(...$events);

        $this->assertEquals([], $actualEventTeacherArray);
    }

    public function test_if_assigns_teachers_when_multiple_teachers_present() : void
    {
        $events = [];

        $teacher0 = TeacherMother::withMapId(0);
        $teacher1 = TeacherMother::withMapId(1);
        $teacher2 = TeacherMother::withMapId(2);

        $subject0 = SubjectMother::withTeacher($teacher0);
        $events[] = EventMother::withMapId(0)->setSubject($subject0);
        $events[] = EventMother::withMapId(1)->setSubject($subject0);
        $events[] = EventMother::withMapId(2)->setSubject($subject0);
        $events[] = EventMother::withMapId(3)->setSubject($subject0);
        $events[] = EventMother::withMapId(4)->setSubject($subject0);

        $subject1 = SubjectMother::withTeacher($teacher1);
        $events[] = EventMother::withMapId(5)->setSubject($subject1);

        $subject2 = SubjectMother::withTeacher($teacher2);
        $events[] = EventMother::withMapId(6)->setSubject($subject2);
        $events[] = EventMother::withMapId(7)->setSubject($subject2);

        $subject3 = SubjectMother::withTeacher($teacher1);
        $events[] = EventMother::withMapId(8)->setSubject($subject3);
        $events[] = EventMother::withMapId(9)->setSubject($subject3);

        $actualEventTeacherArray = (new EventTeacher())->generate(...$events);

        $this->assertEquals(
            [0, 0, 0, 0, 0, 1, 2, 2, 1, 1],
            $actualEventTeacherArray
        );
    }
}
