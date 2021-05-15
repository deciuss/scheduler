<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduller\Generator;

use PHPUnit\Framework\TestCase;
use App\Scheduler\Generator\EventTeacher;
use App\Scheduler\Generator\TimeslotNeighborNext;
use App\Tests\Mother\EventMother;
use App\Tests\Mother\SubjectMother;
use App\Tests\Mother\TeacherMother;
use App\Tests\Mother\TimeslotMother;

/**
 * @covers \App\Scheduler\Generator\TimeslotNeighborNext
 */
class TimeslotNeighborNextTest extends TestCase
{

    public function test_if_generates_empty_output_when_no_data_present() : void
    {
        $timeslots = [];

        $actualTimeslotNeighborNextArray = (new TimeslotNeighborNext())->generate(...$timeslots);

        $this->assertEquals([], $actualTimeslotNeighborNextArray);

    }

    public function test_if_marks_as_next_when_timeslots_are_neighbors() : void
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

    public function test_if_not_marks_as_next_when_timeslots_are_not_neighbors() : void
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
