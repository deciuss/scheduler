<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Handler\CalculateScheduleChain\EventFillingHandler;

use App\Scheduler\CountResultImporter;
use App\Scheduler\Handler\CalculateScheduleChain\CalculationHandler;
use App\Scheduler\Handler\CalculateScheduleChain\ResultImportHandler\DefaultResultImportHandler;
use App\Scheduler\Message\CalculateSchedule;
use App\Tests\Unit\Scheduler\Handler\ScheduleCalculatorChainAbstractTest;
use Psr\Log\LoggerInterface;

/**
 * @covers \App\Scheduler\Handler\CalculateScheduleChain\ResultImportHandler\DefaultResultImportHandler
 */
class DefaultResultImportHandlerTest extends ScheduleCalculatorChainAbstractTest
{
    public function test_handles_result_import() : void
    {
        $planStatusStateMachineMock = $this->createPlanStatusStateMachineMock(
            $planId = 1,
            'result_import_starting',
            'result_import_finishing'
        );

        $calculateScheduleMessageStub = $this->createStub(CalculateSchedule::class);
        $calculateScheduleMessageStub->method('getPlanId')->willReturn($planId);

        (new DefaultResultImportHandler(
            $planStatusStateMachineMock,
            $this->createStub(CountResultImporter::class),
            $this->createStub(CalculationHandler::class),
            $this->createStub(LoggerInterface::class)
        ))->executeHandler($calculateScheduleMessageStub);
    }
}
