<?php

declare(strict_types=1);

namespace App\Tests\Unit\ScheduleCalculator\Generator;

use App\ScheduleCalculator\Generator\EventGroups;
use App\Tests\Mother\EventMother;
use App\Tests\Mother\StudentGroupMother;
use App\Tests\Mother\SubjectMother;
use App\Tests\Unit\TestCase;

/**
 * @covers \App\ScheduleCalculator\Generator\EventGroups
 */
class EventGroupsTest extends TestCase
{
    public function test_if_generates_empty_output_when_no_data_present() : void
    {
        $events = [];

        $eventGroups = new EventGroups();
        $actualEventGroupsArray = $eventGroups->generate(...$events);

        $this->assertEquals([], $actualEventGroupsArray);
    }

    public function test_if_generates_proper_output_when_data_present() : void
    {
        $lecture = SubjectMother::create(5, 2);
        $this->givenSubjectHasEvents($lecture, ...[
            $events[] = EventMother::create(0),
            $events[] = EventMother::create(1),
            $events[] = EventMother::create(2),
            $events[] = EventMother::create(3),
            $events[] = EventMother::create(4)
        ]);

        $laboratory = SubjectMother::create(2, 1);
        $this->givenSubjectHasEvents($laboratory, ...[
            $events[] = EventMother::create(5)
        ]);

        $exercises = SubjectMother::create(6, 3);
        $this->givenSubjectHasEvents($exercises, ...[
            $events[] = EventMother::create(6),
            $events[] = EventMother::create(7)
        ]);

        $parentGroup = StudentGroupMother::create(0);
        StudentGroupMother::create(1, $parentGroup);
        $this->givenStudentGroupHasSubjects($parentGroup, ...[$lecture]);

        $lonelyGroup = StudentGroupMother::create(2);
        $this->givenStudentGroupHasSubjects($lonelyGroup, ...[$laboratory, $exercises]);

        $eventGroups = new EventGroups();
        $actualEventGroupsArray = $eventGroups->generate(...$events);

        $this->assertEquals(
            [
                [0, 1],
                [0, 1],
                [0, 1],
                [0, 1],
                [0, 1],
                [2],
                [2],
                [2]
            ],
            $actualEventGroupsArray
        );
    }
}
