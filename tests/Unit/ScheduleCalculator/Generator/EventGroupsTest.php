<?php

declare(strict_types=1);

namespace App\Tests\Unit\ScheduleCalculator\Generator;

use App\ScheduleCalculator\Generator\EventGroups;
use App\Tests\Stub\Mother\EventMother;
use App\Tests\Stub\Mother\StudentGroupMother;
use App\Tests\Stub\Mother\SubjectMother;
use App\Tests\Unit\TestCase;

/**
 * @covers \App\ScheduleCalculator\Generator\EventGroups
 */
class EventGroupsTest extends TestCase
{
    public function test_if_generates_empty_output_when_no_data_present() : void
    {
        $events = [];

        $actualEventGroupsArray = (new EventGroups())->generate(...$events);

        $this->assertEquals([], $actualEventGroupsArray);
    }

    public function test_if_assigning_child_group_to_parents_event() : void
    {
        $events = [];

        $this->givenSubjectHasEvents(
            $subject = SubjectMother::withHoursWithBlockSize(5, 2),
            $events[] = EventMother::withMapId(0),
            $events[] = EventMother::withMapId(1),
            $events[] = EventMother::withMapId(2)
        );

        $this->givenStudentGroupHasSubjects(
            $parentGroup = StudentGroupMother::withMapIdWithParent(0),
            $subject
        );

        StudentGroupMother::withMapIdWithParent(1, $parentGroup);

        $actualEventGroupsArray = (new EventGroups())->generate(...$events);

        $this->assertEquals(
            [
                [0, 1],
                [0, 1],
                [0, 1]
            ],
            $actualEventGroupsArray
        );
    }

    public function test_if_not_assigning_parent_group_to_childs_event() : void
    {
        $events = [];

        $this->givenSubjectHasEvents(
            $subject = SubjectMother::withHoursWithBlockSize(5, 2),
            $events[] = EventMother::withMapId(0),
            $events[] = EventMother::withMapId(1),
            $events[] = EventMother::withMapId(2)
        );

        $parentGroup = StudentGroupMother::withMapIdWithParent(0);

        $this->givenStudentGroupHasSubjects(
            StudentGroupMother::withMapIdWithParent(1, $parentGroup),
            $subject
        );

        $actualEventGroupsArray = (new EventGroups())->generate(...$events);

        $this->assertEquals(
            [
                [1],
                [1],
                [1]
            ],
            $actualEventGroupsArray
        );
    }

    public function test_if_assigning_groups_when_multiple_groups_present() : void
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
            $laboratory = SubjectMother::withHoursWithBlockSize(2, 1),
            $events[] = EventMother::withMapId(5)
        );

        $this->givenSubjectHasEvents(
            $exercises = SubjectMother::withHoursWithBlockSize(6, 3),
            $events[] = EventMother::withMapId(6),
            $events[] = EventMother::withMapId(7)
        );

        $this->givenStudentGroupHasSubjects(
            $parentGroup = StudentGroupMother::withMapIdWithParent(0),
            $lecture
        );

        StudentGroupMother::withMapIdWithParent(1, $parentGroup);

        $this->givenStudentGroupHasSubjects(
            StudentGroupMother::withMapIdWithParent(2),
            $laboratory,
            $exercises
        );

        $actualEventGroupsArray = (new EventGroups())->generate(...$events);

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
