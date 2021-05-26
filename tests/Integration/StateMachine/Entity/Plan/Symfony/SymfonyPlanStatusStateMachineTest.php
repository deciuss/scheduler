<?php

declare(strict_types=1);

namespace App\Tests\Integration\StateMachine\Entity\Plan\Symfony;

use App\DBAL\PlanStatus;
use App\StateMachine\Entity\Plan\Symfony\SymfonyPlanStatusStateMachine;
use App\Tests\Integration\IntegrationTestCase;

/**
 * @covers \App\StateMachine\Entity\Plan\Symfony\SymfonyPlanStatusStateMachine
 */
class SymfonyPlanStatusStateMachineTest extends IntegrationTestCase
{

    public function test_state_machine_allows_transition_if_it_is_possible() : void
    {
        $plan = $this->schedulerContext->givenPlanExists('plan', PlanStatus::PLAN_STATUS_LOCKED);

        $result = (new SymfonyPlanStatusStateMachine(
            $this->schedulerContext->getPlanStatusStateMachine(),
            $this->schedulerContext->getPlanRepository()
        ))->can($plan->getId(), 'event_filling_starting');

        $this->assertTrue($result);
    }

    public function test_state_machine_does_not_allow_transition_if_it_is_not_possible() : void
    {
        $plan = $this->schedulerContext->givenPlanExists('plan', PlanStatus::PLAN_STATUS_LOCKED);

        $result = (new SymfonyPlanStatusStateMachine(
            $this->schedulerContext->getPlanStatusStateMachine(),
            $this->schedulerContext->getPlanRepository()
        ))->can($plan->getId(), 'map_id_filling_starting');

        $this->assertFalse($result);
    }

    public function test_state_machine_persists_state() : void
    {
        $plan = $this->schedulerContext->givenPlanExists('plan', PlanStatus::PLAN_STATUS_LOCKED);

        (new SymfonyPlanStatusStateMachine(
            $this->schedulerContext->getPlanStatusStateMachine(),
            $this->schedulerContext->getPlanRepository()
        ))->apply($plan->getId(), 'event_filling_starting');

        $this->schedulerContext->getEntityManager()->clear();

        $this->assertEquals(
            PlanStatus::PLAN_STATUS_EVENT_FILLING_STARTED,
            $this->schedulerContext->getPlanRepository()->findOneBy(['id' => $plan->getId()])->getStatus()
        );
    }

    public function test_state_machine_confirms_current_state() : void
    {
        $plan = $this->schedulerContext->givenPlanExists('plan', PlanStatus::PLAN_STATUS_LOCKED);

        $result = (new SymfonyPlanStatusStateMachine(
            $this->schedulerContext->getPlanStatusStateMachine(),
            $this->schedulerContext->getPlanRepository()
        ))->is($plan->getId(), 'locked');

        $this->assertTrue($result);
    }
}
