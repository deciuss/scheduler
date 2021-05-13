<?php

declare(strict_types=1);

namespace App\Tests\Unit\ScheduleCalculator\Generator;

use App\Entity\Event;
use App\Entity\Subject;
use App\ScheduleCalculator\Generator\EventBlock;

/**
 * @covers \App\ScheduleCalculator\Generator\EventBlock
 */
class EventBlockTest extends \PHPUnit\Framework\TestCase
{
    public function test_if_generates_proper_output() : void
    {
        $subjects = [
            (new Subject())
                ->setHours(5)
                ->setBlockSize(2)
                ->addEvent((new Event())->setMapId(0))
                ->addEvent((new Event())->setMapId(1))
                ->addEvent((new Event())->setMapId(2))
                ->addEvent((new Event())->setMapId(3))
                ->addEvent((new Event())->setMapId(4)),
            (new Subject())
                ->setHours(2)
                ->setBlockSize(1)
                ->addEvent((new Event())->setMapId(5))
                ->addEvent((new Event())->setMapId(6)),
            (new Subject())
                ->setHours(6)
                ->setBlockSize(3)
                ->addEvent((new Event())->setMapId(7))
                ->addEvent((new Event())->setMapId(8))
                ->addEvent((new Event())->setMapId(9))
                ->addEvent((new Event())->setMapId(10))
                ->addEvent((new Event())->setMapId(11))
                ->addEvent((new Event())->setMapId(12))
        ];

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
