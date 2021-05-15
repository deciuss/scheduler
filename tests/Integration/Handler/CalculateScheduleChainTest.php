<?php

declare(strict_types=1);

namespace App\Tests\Integration\Scheduler\Handler;

use App\DBAL\PlanStatus;
use App\Scheduler\Message\CalculateSchedule;
use App\Tests\Integration\IntegrationTestCase;

/**
 * @covers \App\Scheduler\Infrastructure\Handler\CalculateScheduleChain\
 * @covers \App\Scheduler\Infrastructure\Handler\CalculateScheduleHandler
 */
class CalculateScheduleChainTest extends IntegrationTestCase
{

    public function test_if_handlers_go_through_whole_chain_when_status_is_not_an_error() : void
    {
        $plan = $this->schedulerContext->givenPlanExists(
            'plan',
            PlanStatus::PLAN_STATUS_UNDER_CONSTRUCTION
        );

        ($this->schedulerContext->getCalculateScheduleHandler())(new CalculateSchedule($plan));

        $this->assertEquals(
            PlanStatus::PLAN_STATUS_SCHEDULE_CALCULATION_FINISHED,
            ($this->schedulerContext->getPlanRepository()->findOneBy(['id' => $plan->getId()]))->getStatus()
        );
    }

}