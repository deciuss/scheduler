<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Normalization\Generator\EventRoomFit;

use App\Scheduler\Normalization\Generator\EventRoomFit\RoomHasRequiredCapacity;
use App\Tests\Fake\Mother\StudentGroupMother;
use PHPUnit\Framework\TestCase;
use App\Scheduler\Normalization\Generator\EventRoomFit\RoomHasRequiredFeatures;
use App\Tests\Fake\Mother\EventMother;
use App\Tests\Fake\Mother\FeatureMother;
use App\Tests\Fake\Mother\RoomMother;
use App\Tests\Fake\Mother\SubjectMother;

/**
 * @covers \App\Scheduler\Normalization\Generator\EventRoomFit\RoomHasRequiredCapacity
 */
class RoomHasRequiredCapacityTest extends TestCase
{

    public function test_gives_positive_result_when_room_has_capacity_required_for_hosting_event()
    {
        $event = EventMother::withSubject(
            SubjectMother::withStudentGroup(
                StudentGroupMother::withCardinality($numberOfStudents = 20)
            )
        );

        $room = RoomMother::withCapacity($numberOfStudents);

        $actualRoomHasRequiredCapacityValue = (new RoomHasRequiredCapacity())->check($event, $room);

        $this->assertTrue($actualRoomHasRequiredCapacityValue);
    }

    public function test_gives_negative_result_when_room_does_not_have_capacity_required_by_event()
    {
        $event = EventMother::withSubject(
            SubjectMother::withStudentGroup(
                StudentGroupMother::withCardinality($numberOfStudents = 20)
            )
        );

        $room = RoomMother::withCapacity($numberOfStudents - 1);

        $actualRoomHasRequiredCapacityValue = (new RoomHasRequiredCapacity())->check($event, $room);

        $this->assertFalse($actualRoomHasRequiredCapacityValue);
    }
}
