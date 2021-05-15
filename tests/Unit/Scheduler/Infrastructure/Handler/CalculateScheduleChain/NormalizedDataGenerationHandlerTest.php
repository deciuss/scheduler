<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Infrastructure\Handler\CalculateScheduleChain;

use App\DBAL\PlanStatus;
use App\Entity\Plan;
use App\Repository\PlanRepository;
use App\Scheduler\Handler\CalculateScheduleChain\MapIdFillingHandler;
use App\Scheduler\Infrastructure\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler;
use App\Scheduler\Message\CalculateSchedule;
use App\Scheduler\NormalizedDataGenerator;
use App\Tests\Stub\MessageBusStub;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \App\Scheduler\Infrastructure\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler
 */
class NormalizedDataGenerationHandlerTest extends TestCase
{
    public function test_if_handles_when_plan_status_is_map_id_filling_finished() : void
    {
        $planMock = $this->getMockBuilder(Plan::class)->getMock();
        $planMock->method("getId")->willReturn(1);
        $planMock->method("getStatus")->willReturn(PlanStatus::PLAN_STATUS_MAP_ID_FILLING_FINISHED);
        $planMock->expects($this->exactly(2))->method("setStatus")->withConsecutive(
            [PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_STARTED],
            [PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED]
        );

        $planRepositoryStub = $this->createStub(PlanRepository::class);
        $planRepositoryStub->method("findOneBy")->willReturn($planMock);

        (new NormalizedDataGenerationHandler(
            $this->createStub(MapIdFillingHandler::class),
            $this->createStub(LoggerInterface::class),
            new MessageBusStub(),
            $this->createStub(EntityManagerInterface::class),
            $planRepositoryStub,
            $this->createStub(NormalizedDataGenerator::class)
        ))->executeHandler(new CalculateSchedule($planMock));
    }
}
