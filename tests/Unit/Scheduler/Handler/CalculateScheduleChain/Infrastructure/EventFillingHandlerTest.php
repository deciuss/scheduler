<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Handler\CalculateScheduleChain\Infrastructure;

use App\DBAL\PlanStatus;
use App\Repository\PlanRepository;
use App\Scheduler\EventFiller;
use App\Scheduler\Handler\CalculateScheduleChain\LockHandler;
use App\Scheduler\Handler\CalculateScheduleChain\Infrastructure\EventFillingHandler;
use App\Scheduler\Message\CalculateSchedule;
use App\Tests\Stub\MessageBusStub;
use App\Tests\Unit\Scheduler\Handler\ScheduleCalculatorChainAbstractTest;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \App\Scheduler\Handler\CalculateScheduleChain\Infrastructure\EventFillingHandler
 */
class EventFillingHandlerTest extends ScheduleCalculatorChainAbstractTest
{
    public function test_if_handles_when_plan_status_is_locked() : void
    {
        $planMock = $this->createPlanMockWithStatusAndExpectingStatusChanges(
            PlanStatus::PLAN_STATUS_LOCKED,
            PlanStatus::PLAN_STATUS_EVENT_FILLING_STARTED,
            PlanStatus::PLAN_STATUS_EVENT_FILLING_FINISHED,
        );

        $planRepositoryStub = $this->createStub(PlanRepository::class);
        $planRepositoryStub->method("findOneBy")->willReturn($planMock);

        (new EventFillingHandler(
            $this->createStub(LockHandler::class),
            $this->createStub(LoggerInterface::class),
            new MessageBusStub(),
            $this->createStub(EntityManagerInterface::class),
            $planRepositoryStub,
            $this->createStub(EventFiller::class)
        ))->executeHandler(new CalculateSchedule($planMock));
    }
}
