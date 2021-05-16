<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Infrastructure\Handler\CalculateScheduleChain;

use App\DBAL\PlanStatus;
use App\Repository\PlanRepository;
use App\Scheduler\Handler\CalculateScheduleChain\EventFillingHandler;
use App\Scheduler\Infrastructure\Handler\CalculateScheduleChain\MapIdFillingHandler;
use App\Scheduler\Message\CalculateSchedule;
use App\Tests\Stub\MessageBusStub;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Scheduler\MapIdFiller;

/**
 * @covers \App\Scheduler\Infrastructure\Handler\CalculateScheduleChain\MapIdFillingHandler
 */
class MapIdFillingHandlerTestTest extends ScheduleCalculatorChainAbstractTest
{
    public function test_if_handles_when_plan_status_is_locked() : void
    {
        $planMock = $this->createPlanMockWithStatusAndExpectingStatusChanges(
            PlanStatus::PLAN_STATUS_EVENT_FILLING_FINISHED,
            PlanStatus::PLAN_STATUS_MAP_ID_FILLING_STARTED,
            PlanStatus::PLAN_STATUS_MAP_ID_FILLING_FINISHED,
        );

        $planRepositoryStub = $this->createStub(PlanRepository::class);
        $planRepositoryStub->method("findOneBy")->willReturn($planMock);

        (new MapIdFillingHandler(
            $this->createStub(EventFillingHandler::class),
            $this->createStub(LoggerInterface::class),
            new MessageBusStub(),
            $this->createStub(EntityManagerInterface::class),
            $planRepositoryStub,
            $this->createStub(MapIdFiller\EventFiller::class),
            $this->createStub(MapIdFiller\RoomFiller::class),
            $this->createStub(MapIdFiller\StudentGroupFiller::class),
            $this->createStub(MapIdFiller\TeacherFiller::class),
            $this->createStub(MapIdFiller\TimeslotFiller::class)
        ))->executeHandler(new CalculateSchedule($planMock));
    }
}