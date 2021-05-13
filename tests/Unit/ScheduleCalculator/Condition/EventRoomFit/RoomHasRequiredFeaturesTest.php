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

    public function test_check_gives_positive_result_if_room_has_all_features_required_by_event()
    {
        $features = [];

        $this->givenSubjectHasEvents(
            $subject = SubjectMother::create(),
            $event = EventMother::create()
        );

        $this->givenSubjectRequiresFeatures(
            $subject,
            $features[0] = FeatureMother::create(),
            $features[1] = FeatureMother::create()
        );

        $this->givenRoomHasFeatures(
            $room = RoomMother::create(),
            ...$features
        );

        $actualRoomHasRequiredFeaturesValue = (new RoomHasRequiredFeatures())->check($event, $room);

        $this->assertTrue($actualRoomHasRequiredFeaturesValue);
    }

    public function test_check_gives_negative_result_if_room_does_not_have_all_features_required_by_event()
    {
        $features = [];

        $this->givenSubjectHasEvents(
            $subject = SubjectMother::create(),
            $event = EventMother::create()
        );

        $this->givenSubjectRequiresFeatures(
            $subject,
            $features[0] = FeatureMother::create(),
            $features[1] = FeatureMother::create()
        );

        $this->givenRoomHasFeatures(
            $room = RoomMother::create(),
            $features[0]
        );

        $actualRoomHasRequiredFeaturesValue = (new RoomHasRequiredFeatures())->check($event, $room);

        $this->assertFalse($actualRoomHasRequiredFeaturesValue);
    }

}
