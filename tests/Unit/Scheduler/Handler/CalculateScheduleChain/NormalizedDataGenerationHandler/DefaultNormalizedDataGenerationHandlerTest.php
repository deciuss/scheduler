<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler;

use App\DBAL\PlanStatus;
use App\Repository\PlanRepository;
use App\Scheduler\Handler\CalculateScheduleChain\MapIdFillingHandler;
use App\Scheduler\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler\DefaultNormalizedDataGenerationHandler;
use App\Scheduler\Message\CalculateSchedule;
use App\Scheduler\Normalization\NormalizedDataGenerator;
use App\Tests\Stub\MessageBusStub;
use App\Tests\Unit\Scheduler\Handler\ScheduleCalculatorChainAbstractTest;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \App\Scheduler\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler\DefaultNormalizedDataGenerationHandler
 */
class DefaultNormalizedDataGenerationHandlerTest extends ScheduleCalculatorChainAbstractTest
{
    public function test_handles_normalized_data_generation() : void
    {
        $planStatusStateMachineMock = $this->createPlanStatusStateMachineMock(
            $planId = 1,
            'normalized_data_generation_starting',
            'normalized_data_generation_finishing'
        );

        $calculateScheduleMessageStub = $this->createStub(CalculateSchedule::class);
        $calculateScheduleMessageStub->method('getPlanId')->willReturn($planId);

        (new DefaultNormalizedDataGenerationHandler(
            $planStatusStateMachineMock,
            new MessageBusStub(),
            $this->createStub(NormalizedDataGenerator::class),
            $this->createStub(LoggerInterface::class),
            $this->createStub(MapIdFillingHandler::class)
        ))->executeHandler($calculateScheduleMessageStub);
    }
}
