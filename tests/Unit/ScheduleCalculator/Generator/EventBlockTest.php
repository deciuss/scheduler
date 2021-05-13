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

        $this->givenSubjectHasEvents(
            $subjects[] = SubjectMother::create(5, 2),
            EventMother::create(0),
            EventMother::create(1),
            EventMother::create(2),
            EventMother::create(3),
            EventMother::create(4)
        );

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
            $subjects[] = SubjectMother::create(1, 1),
            EventMother::create(0)
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
            $subjects[] = SubjectMother::create(5, 2),
            EventMother::create(0),
            EventMother::create(1),
            EventMother::create(2),
            EventMother::create(3),
            EventMother::create(4)
        );

        $this->givenSubjectHasEvents(
            $subjects[] = SubjectMother::create(2, 1),
            EventMother::create(5),
            EventMother::create(6)
        );

        $this->givenSubjectHasEvents(
            $subjects[] = SubjectMother::create(6, 3),
            EventMother::create(7),
            EventMother::create(8),
            EventMother::create(9),
            EventMother::create(10),
            EventMother::create(11),
            EventMother::create(12)
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
