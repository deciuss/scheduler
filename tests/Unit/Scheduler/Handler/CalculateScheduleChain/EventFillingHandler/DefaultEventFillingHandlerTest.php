<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Handler\CalculateScheduleChain\EventFillingHandler;

use App\Scheduler\Handler\CalculateScheduleChain\EventFillingHandler\DefaultEventFillingHandler;
use App\Scheduler\Normalization\EventFiller;
use App\Scheduler\Handler\CalculateScheduleChain\LockHandler;
use App\Scheduler\Message\CalculateSchedule;
use App\Tests\Stub\MessageBusStub;
use App\Tests\Unit\Scheduler\Handler\ScheduleCalculatorChainAbstractTest;
use Psr\Log\LoggerInterface;

/**
 * @covers \App\Scheduler\Handler\CalculateScheduleChain\EventFillingHandler\DefaultEventFillingHandler
 */
class DefaultEventFillingHandlerTest extends ScheduleCalculatorChainAbstractTest
{
    public function test_if_handles_when_plan_status_is_locked() : void
    {
        $planStatusStateMachineMock = $this->createPlanStatusStateMachineMock(
            $planId = 1,
            'event_filling_starting',
            'event_filling_finishing'
        );

        $calculateScheduleMessageStub = $this->createStub(CalculateSchedule::class);
        $calculateScheduleMessageStub->method('getPlanId')->willReturn($planId);

        (new DefaultEventFillingHandler(
            $planStatusStateMachineMock,
            new MessageBusStub(),
            $this->createStub(EventFiller::class),
            $this->createStub(LockHandler::class),
            $this->createStub(LoggerInterface::class),
        ))->executeHandler($calculateScheduleMessageStub);
    }
}
