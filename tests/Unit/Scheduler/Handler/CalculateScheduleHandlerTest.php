<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Handler;

use App\DBAL\PlanStatus;
use App\Repository\PlanRepository;
use App\Scheduler\CalculatorExecutor;
use App\Scheduler\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler;
use App\Scheduler\Handler\CalculateScheduleHandler;
use App\Scheduler\Message\CalculateSchedule;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \App\Scheduler\Handler\CalculateScheduleHandler
 */
class CalculateScheduleHandlerTest extends ScheduleCalculatorChainAbstractTest
{
    public function test_if_handles_when_plan_status_is_normalized_data_generation_finished() : void
    {
        $planMock = $this->createPlanMockWithStatusAndExpectingStatusChanges(
            PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED,
            PlanStatus::PLAN_STATUS_SCHEDULE_CALCULATION_STARTED,
            PlanStatus::PLAN_STATUS_SCHEDULE_CALCULATION_FINISHED,
        );

        $planRepositoryStub = $this->createStub(PlanRepository::class);
        $planRepositoryStub->method("findOneBy")->willReturn($planMock);

        (new CalculateScheduleHandler(
            $this->createStub(NormalizedDataGenerationHandler::class),
            $this->createStub(LoggerInterface::class),
            $this->createStub(EntityManagerInterface::class),
            $planRepositoryStub,
            $this->createStub(CalculatorExecutor::class)
        ))->executeHandler(new CalculateSchedule($planMock));
    }

    public function test_if_handles_when_plan_status_is_schedule_calculation_finished() : void
    {
        $planMock = $this->createPlanMockWithStatusAndExpectingStatusChanges(
            PlanStatus::PLAN_STATUS_SCHEDULE_CALCULATION_FINISHED,
            PlanStatus::PLAN_STATUS_SCHEDULE_CALCULATION_STARTED,
            PlanStatus::PLAN_STATUS_SCHEDULE_CALCULATION_FINISHED,
            );

        $planRepositoryStub = $this->createStub(PlanRepository::class);
        $planRepositoryStub->method("findOneBy")->willReturn($planMock);

        (new CalculateScheduleHandler(
            $this->createStub(NormalizedDataGenerationHandler::class),
            $this->createStub(LoggerInterface::class),
            $this->createStub(EntityManagerInterface::class),
            $planRepositoryStub,
            $this->createStub(CalculatorExecutor::class)
        ))->executeHandler(new CalculateSchedule($planMock));
    }
}
