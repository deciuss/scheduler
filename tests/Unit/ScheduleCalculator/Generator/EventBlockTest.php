<?php

declare(strict_types=1);

namespace App\Tests\Unit\ScheduleCalculator\Generator;

use App\ScheduleCalculator\Generator\EventBlock;
use App\Tests\Mother\EventMother;
use App\Tests\Mother\SubjectMother;
use App\Tests\Unit\TestCase;

/**
 * @covers \App\ScheduleCalculator\Generator\EventBlock
 */
class EventBlockTest extends TestCase
{

    public function test_if_generates_empty_output_when_no_data_present() : void
    {
        $subjects = [];

        $eventGroups = new EventBlock();
        $actualEventBlockArray = $eventGroups->generate(...$subjects);

        $this->assertEquals([], $actualEventBlockArray);
    }

    public function test_if_generates_proper_output_when_data_present() : void
    {
        $subjects[] = SubjectMother::create(5, 2);
        $this->givenSubjectHasEvents($subjects[0], ...[
            EventMother::create(0),
            EventMother::create(1),
            EventMother::create(2),
            EventMother::create(3),
            EventMother::create(4),
        ]);

        $subjects[] = SubjectMother::create(2, 1);
        $this->givenSubjectHasEvents($subjects[1], ...[
            EventMother::create(5),
            EventMother::create(6),
        ]);

        $subjects[] = SubjectMother::create(6, 3);
        $this->givenSubjectHasEvents($subjects[2], ...[
            EventMother::create(7),
            EventMother::create(8),
            EventMother::create(9),
            EventMother::create(10),
            EventMother::create(11),
            EventMother::create(12),
        ]);

        $eventBlock = new EventBlock();
        $actualEventBlockArray = $eventBlock->generate(...$subjects);

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
