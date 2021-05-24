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
    public function test_if_handles_when_plan_status_is_map_id_filling_finished() : void
    {
        $planMock = $this->createPlanMockWithStatusAndExpectingStatusChanges(
            PlanStatus::PLAN_STATUS_MAP_ID_FILLING_FINISHED,
            PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_STARTED,
            PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED,
        );

        $planRepositoryStub = $this->createStub(PlanRepository::class);
        $planRepositoryStub->method("findOneBy")->willReturn($planMock);

        (new DefaultNormalizedDataGenerationHandler(
            $this->createStub(MapIdFillingHandler::class),
            $this->createStub(LoggerInterface::class),
            new MessageBusStub(),
            $this->createStub(EntityManagerInterface::class),
            $planRepositoryStub,
            $this->createStub(NormalizedDataGenerator::class)
        ))->executeHandler(new CalculateSchedule($planMock));
    }
}
