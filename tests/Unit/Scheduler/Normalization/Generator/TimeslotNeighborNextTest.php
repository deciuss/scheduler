<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Normalization\Generator;

use PHPUnit\Framework\TestCase;
use App\Scheduler\Normalization\Generator\EventTeacher;
use App\Scheduler\Normalization\Generator\TimeslotNeighborNext;
use App\Tests\Fake\Mother\EventMother;
use App\Tests\Fake\Mother\SubjectMother;
use App\Tests\Fake\Mother\TeacherMother;
use App\Tests\Fake\Mother\TimeslotMother;

/**
 * @covers \App\Scheduler\Normalization\Generator\TimeslotNeighborNext
 */
class TimeslotNeighborNextTest extends TestCase
{
    public function test_generates_empty_output_when_no_data_present() : void
    {
        $timeslots = [];

        $actualTimeslotNeighborNextArray = (new TimeslotNeighborNext())->generate(...$timeslots);

        $this->assertEquals([], $actualTimeslotNeighborNextArray);

    }

    public function test_marks_as_next_when_timeslots_are_neighbors() : void
    {
        $timeslots = [];

        $timeslots[] = (TimeslotMother::withMapId(0))
            ->setStart(new \DateTime("2000-01-01 00:00:00"))
            ->setEnd(new \DateTime("2000-01-01 00:05:00"));

        $timeslots[] = (TimeslotMother::withMapId(1))
            ->setStart(new \DateTime("2000-01-01 00:05:00"))
            ->setEnd(new \DateTime("2000-01-01 00:10:00"));

        $actualTimeslotNeighborNextArray = (new TimeslotNeighborNext())->generate(...$timeslots);

        $this->assertEquals([1, -1], $actualTimeslotNeighborNextArray);
    }

    public function test_not_marks_as_next_when_timeslots_are_not_neighbors() : void
    {
        $timeslots = [];

        $timeslots[] = (TimeslotMother::withMapId(0))
            ->setStart(new \DateTime("2000-01-01 00:00:00"))
            ->setEnd(new \DateTime("2000-01-01 00:05:00"));

        $timeslots[] = (TimeslotMother::withMapId(1))
            ->setStart(new \DateTime("2000-01-01 00:05:01"))
            ->setEnd(new \DateTime("2000-01-01 00:10:00"));

        $actualTimeslotNeighborNextArray = (new TimeslotNeighborNext())->generate(...$timeslots);

        $this->assertEquals([-1, -1], $actualTimeslotNeighborNextArray);
    }
}
