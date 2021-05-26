<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Handler\CalculateScheduleChain\MapIdFillingHandler;

use App\Scheduler\Handler\CalculateScheduleChain\EventFillingHandler;
use App\Scheduler\Handler\CalculateScheduleChain\MapIdFillingHandler\DefaultMapIdFillingHandler;
use App\Scheduler\Message\CalculateSchedule;
use App\Tests\Stub\MessageBusStub;
use App\Tests\Unit\Scheduler\Handler\ScheduleCalculatorChainAbstractTest;
use Psr\Log\LoggerInterface;
use App\Scheduler\Normalization\MapIdFiller;

/**
 * @covers \App\Scheduler\Handler\CalculateScheduleChain\MapIdFillingHandler\DefaultMapIdFillingHandler
 */
class DefaultMapIdFillingHandlerTestTest extends ScheduleCalculatorChainAbstractTest
{
    public function test_if_handles_when_plan_status_is_event_filling_finished() : void
    {
        $planStatusStateMachineMock = $this->createPlanStatusStateMachineMock(
            $planId = 1,
            'map_id_filling_starting',
            'map_id_filling_finishing'
        );

        $calculateScheduleMessageStub = $this->createStub(CalculateSchedule::class);
        $calculateScheduleMessageStub->method('getPlanId')->willReturn($planId);

        (new DefaultMapIdFillingHandler(
            $planStatusStateMachineMock,
            new MessageBusStub(),
            $this->createStub(MapIdFiller\EventFiller::class),
            $this->createStub(MapIdFiller\RoomFiller::class),
            $this->createStub(MapIdFiller\StudentGroupFiller::class),
            $this->createStub(MapIdFiller\TeacherFiller::class),
            $this->createStub(MapIdFiller\TimeslotFiller::class),
            $this->createStub(EventFillingHandler::class),
            $this->createStub(LoggerInterface::class)
        ))->executeHandler($calculateScheduleMessageStub);
    }
}
