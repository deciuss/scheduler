<?php

declare(strict_types=1);

namespace App\Tests\Integration\Scheduler\Handler;

use App\DBAL\PlanStatus;
use App\Scheduler\Message\CalculateSchedule;
use App\Tests\Integration\IntegrationTestCase;

/**
 * @covers \App\Scheduler\Handler\CalculateScheduleChain\Infrastructure\
 * @covers \App\Scheduler\Handler\CalculateScheduleHandler
 */
class CalculateScheduleChainTest extends IntegrationTestCase
{
    public function test_if_handlers_goes_through_whole_chain() : void
    {
        $plan = $this->schedulerContext->givenPlanExists(
            'plan',
            PlanStatus::PLAN_STATUS_LOCKED
        );

        ($this->schedulerContext->getCalculateScheduleHandler())(new CalculateSchedule($plan));

        $this->assertEquals(
            PlanStatus::PLAN_STATUS_RESULT_IMPORT_FINISHED,
            ($this->schedulerContext->getPlanRepository()->findOneBy(['id' => $plan->getId()]))->getStatus()
        );
    }

}