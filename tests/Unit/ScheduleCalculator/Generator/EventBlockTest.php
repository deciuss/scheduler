<?php

declare(strict_types=1);

namespace App\Tests\Unit\ScheduleCalculator\Generator;

use App\ScheduleCalculator\Generator\EventBlock;
use App\Tests\Stub\Mother\EventMother;
use App\Tests\Stub\Mother\SubjectMother;
use App\Tests\Unit\TestCase;

/**
 * @covers \App\ScheduleCalculator\Generator\EventBlock
 */
class EventBlockTest extends TestCase
{

    public function test_if_generates_empty_output_when_no_data_present() : void
    {
        $subjects = [];

        $actualEventBlockArray = (new EventBlock())->generate(...$subjects);

        $this->assertEquals([], $actualEventBlockArray);
    }

    public function test_if_separates_for_many_blocks_when_hours_greater_than_block_size() : void
    {
        $subjects = [];

        $subjects[] = SubjectMother::withEvents(
                EventMother::withMapId(0),
                EventMother::withMapId(1),
                EventMother::withMapId(2),
                EventMother::withMapId(3),
                EventMother::withMapId(4)
            )
            ->setHours(5)
            ->setBlockSize(2);


        $actualEventBlockArray = (new EventBlock())->generate(...$subjects);

        $this->assertEquals(
            [
                [0, 1],
                [2, 3],
                [4]
            ],
            $actualEventBlockArray
        );
    }

    public function test_if_separates_for_blocks_when_there_is_only_one_hour() : void
    {
        $subjects = [];

        $this->givenSubjectHasEvents(
            $subjects[] = SubjectMother::withHoursWithBlockSize(1, 1),
            EventMother::withMapId(0)
        );

        $actualEventBlockArray = (new EventBlock())->generate(...$subjects);

        $this->assertEquals(
            [
                [0],
            ],
            $actualEventBlockArray
        );
    }

    public function test_if_separates_for_blocks_when_multiple_subjects_present() : void
    {
        $subjects = [];

        $this->givenSubjectHasEvents(
            $subjects[] = SubjectMother::withHoursWithBlockSize(5, 2),
            EventMother::withMapId(0),
            EventMother::withMapId(1),
            EventMother::withMapId(2),
            EventMother::withMapId(3),
            EventMother::withMapId(4)
        );

        $this->givenSubjectHasEvents(
            $subjects[] = SubjectMother::withHoursWithBlockSize(2, 1),
            EventMother::withMapId(5),
            EventMother::withMapId(6)
        );

        $this->givenSubjectHasEvents(
            $subjects[] = SubjectMother::withHoursWithBlockSize(6, 3),
            EventMother::withMapId(7),
            EventMother::withMapId(8),
            EventMother::withMapId(9),
            EventMother::withMapId(10),
            EventMother::withMapId(11),
            EventMother::withMapId(12)
        );

        $actualEventBlockArray = (new EventBlock())->generate(...$subjects);

        $this->assertEquals(
            [
                [0, 1],
                [2, 3],
                [4],
                [5],
                [6],
                [7, 8, 9],
                [10, 11, 12],
            ],
            $actualEventBlockArray
        );
    }
}
