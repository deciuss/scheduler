<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Handler\CalculateScheduleChain\EventFillingHandler;

use App\Scheduler\CountExecutor;
use App\Scheduler\Handler\CalculateScheduleChain\CalculationHandler\DefaultCalculationHandler;
use App\Scheduler\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler;
use App\Scheduler\Message\CalculateSchedule;
use App\Tests\Stub\MessageBusStub;
use App\Tests\Unit\Scheduler\Handler\ScheduleCalculatorChainAbstractTest;
use Psr\Log\LoggerInterface;

/**
 * @covers \App\Scheduler\Handler\CalculateScheduleChain\CalculationHandler\DefaultCalculationHandler
 */
class DefaultCalculationHandlerTest extends ScheduleCalculatorChainAbstractTest
{
    public function test_handles_calculation() : void
    {
        $planStatusStateMachineMock = $this->createPlanStatusStateMachineMock(
            $planId = 1,
            'calculation_starting',
            'calculation_finishing'
        );

        $calculateScheduleMessageStub = $this->createStub(CalculateSchedule::class);
        $calculateScheduleMessageStub->method('getPlanId')->willReturn($planId);

        (new DefaultCalculationHandler(
            $planStatusStateMachineMock,
            new MessageBusStub(),
            $this->createStub(CountExecutor::class),
            $this->createStub(NormalizedDataGenerationHandler::class),
            $this->createStub(LoggerInterface::class)
        ))->executeHandler($calculateScheduleMessageStub);
    }
}
