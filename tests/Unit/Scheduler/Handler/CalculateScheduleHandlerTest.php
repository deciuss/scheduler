<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Handler;

use App\Scheduler\CalculatorExecutor;
use App\Scheduler\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler;
use App\Scheduler\Handler\CalculateScheduleHandler;
use App\Scheduler\Message\CalculateSchedule;
use Psr\Log\LoggerInterface;

/**
 * @covers \App\Scheduler\Handler\CalculateScheduleHandler
 */
class CalculateScheduleHandlerTest extends ScheduleCalculatorChainAbstractTest
{
    public function test_if_handles_when_plan_status_is_normalized_data_generation_finished() : void
    {

        $planStatusStateMachineMock = $this->createPlanStatusStateMachineMock(
            $planId = 1,
            'schedule_calculation_starting',
            'schedule_calculation_finishing'
        );

        $calculateScheduleMessageStub = $this->createStub(CalculateSchedule::class);
        $calculateScheduleMessageStub->method('getPlanId')->willReturn($planId);

        (new CalculateScheduleHandler(
            $planStatusStateMachineMock,
            $this->createStub(CalculatorExecutor::class),
            $this->createStub(NormalizedDataGenerationHandler::class),
            $this->createStub(LoggerInterface::class)
        ))->executeHandler($calculateScheduleMessageStub);
    }
}
