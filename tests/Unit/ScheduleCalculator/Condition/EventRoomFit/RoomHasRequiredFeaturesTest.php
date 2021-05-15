<?php

declare(strict_types=1);

namespace App\Tests\Unit\ScheduleCalculator\Condition\EventRoomFit;

use App\ScheduleCalculator\Condition\EventRoomFit\RoomHasRequiredFeatures;
use App\Tests\Stub\Mother\EventMother;
use App\Tests\Stub\Mother\FeatureMother;
use App\Tests\Stub\Mother\RoomMother;
use App\Tests\Stub\Mother\SubjectMother;
use App\Tests\Unit\TestCase;

/**
 * @covers \App\ScheduleCalculator\Condition\EventRoomFit\RoomHasRequiredFeatures
 */
class RoomHasRequiredFeaturesTest extends TestCase
{

    public function test_if_gives_positive_result_when_room_has_all_features_required_by_event()
    {
        $features = [];

        $event = EventMother::withSubject(
            SubjectMother::withRequiredFeatures(
                $features[0] = FeatureMother::withId(0),
                $features[1] = FeatureMother::withId(1)
            )
        );

        $room = RoomMother::withFeatures(...$features);

        $actualRoomHasRequiredFeaturesValue = (new RoomHasRequiredFeatures())->check($event, $room);

        $this->assertTrue($actualRoomHasRequiredFeaturesValue);
    }

    public function test_if_gives_negative_result_when_room_does_not_have_all_features_required_by_event()
    {
        $features = [];

        $event = EventMother::withSubject(
            SubjectMother::withRequiredFeatures(
                $features[0] = FeatureMother::withId(0),
                $features[1] = FeatureMother::withId(1)
            )
        );

        $room = RoomMother::withFeatures($features[0]);

        $actualRoomHasRequiredFeaturesValue = (new RoomHasRequiredFeatures())->check($event, $room);

        $this->assertFalse($actualRoomHasRequiredFeaturesValue);
    }

}
