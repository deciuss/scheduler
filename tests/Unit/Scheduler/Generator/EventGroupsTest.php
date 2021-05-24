<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Generator;

use PHPUnit\Framework\TestCase;
use App\Scheduler\Normalization\Generator\EventGroups;
use App\Tests\Fake\Mother\EventMother;
use App\Tests\Fake\Mother\StudentGroupMother;
use App\Tests\Fake\Mother\SubjectMother;

/**
 * @covers \App\Scheduler\Normalization\Generator\EventGroups
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
        $subject = SubjectMother::withStudentGroup(
            StudentGroupMother::withMapId(0)
                ->addChild(StudentGroupMother::withMapId(1))
        );

        $events = [];

        $events[] = EventMother::withMapId(0)->setSubject($subject);
        $events[] = EventMother::withMapId(1)->setSubject($subject);
        $events[] = EventMother::withMapId(2)->setSubject($subject);

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
        $subject = SubjectMother::withStudentGroup(
            StudentGroupMother::withMapId(0)
                ->setParent(StudentGroupMother::withMapId(1))
        );

        $events = [];

        $events[] = EventMother::withMapId(0)->setSubject($subject);
        $events[] = EventMother::withMapId(1)->setSubject($subject);
        $events[] = EventMother::withMapId(2)->setSubject($subject);

        $actualEventGroupsArray = (new EventGroups())->generate(...$events);

        $this->assertEquals(
            [
                [0],
                [0],
                [0]
            ],
            $actualEventGroupsArray
        );
    }

    public function test_if_assigning_groups_when_multiple_groups_present() : void
    {
        $events = [];

        $studentGroup0 = StudentGroupMother::withMapId(0);
        $studentGroup1 = StudentGroupMother::withMapId(1);

        $studentGroup0->addChild($studentGroup1);

        $subject0 = SubjectMother::withStudentGroup($studentGroup0);
        $events[] = EventMother::withMapId(0)->setSubject($subject0);
        $events[] = EventMother::withMapId(1)->setSubject($subject0);

        $subject1 = SubjectMother::withStudentGroup($studentGroup1);
        $events[] = EventMother::withMapId(2)->setSubject($subject1);

        $actualEventGroupsArray = (new EventGroups())->generate(...$events);

        $this->assertEquals(
            [
                [0, 1],
                [0, 1],
                [1]
            ],
            $actualEventGroupsArray
        );
    }
}
