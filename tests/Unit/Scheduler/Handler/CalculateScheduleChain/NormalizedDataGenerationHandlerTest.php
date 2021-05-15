<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduller\Handler\CalculateScheduleChain;

use App\ChainHandler\ChainHandlerAbstract;
use App\DBAL\PlanStatus;
use App\Entity\Plan;
use App\Repository\PlanRepository;
use App\Scheduler\Handler\CalculateScheduleChain\MapIdFillingHandler;
use App\Scheduler\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler;
use App\Scheduler\Message\CalculateSchedule;
use App\Scheduler\NormalizedDataGenerator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @covers \App\Scheduler\Handler\CalculateScheduleChain\NormalizedDataGenerationHandler
 */
class NormalizedDataGenerationHandlerTest extends TestCase
{
    public function test_if_handles_when_plan_status_is_map_id_filling_finished() : void
    {

        $planMock = $this->getMockBuilder(Plan::class)->getMock();
        $planMock->method("getId")->willReturn(1);
        $planMock->expects($this->exactly(2))->method("setStatus")->with(
            PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_STARTED,
            PlanStatus::PLAN_STATUS_NORMALIZED_DATA_GENERATION_FINISHED
        );

        $planRepositoryStub = $this->createStub(PlanRepository::class);
        $planRepositoryStub->method("findOneBy")->willReturn($planMock);

        (new NormalizedDataGenerationHandler(
            $this->createStub(ChainHandlerAbstract::class),
            $this->createStub(LoggerInterface::class),
            $this->createStub(MessageBusInterface::class),
            $this->createStub(EntityManagerInterface::class),
            $planRepositoryStub,
            $this->createStub(NormalizedDataGenerator::class)

        ))->executeHandler(new CalculateSchedule($planMock));

//        (new NormalizedDataGenerationHandler(
//            $this->getMockBuilder(MapIdFillingHandler::class)->disableOriginalConstructor()->disableOriginalClone()->getMock(),
//            $this->getMockBuilder(LoggerInterface::class)->getMock(),
//            $this->getMockBuilder(MessageBusInterface::class)->getMock(),
//            $this->getMockBuilder(EntityManagerInterface::class)->getMock(),
//            $planRepositoryStub,
//            $this->getMockBuilder(NormalizedDataGenerator::class)->disableOriginalConstructor()->getMock()
//
//        ))->executeHandler(new CalculateSchedule($planMock));


    }
}
