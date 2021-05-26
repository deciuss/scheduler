<?php

declare(strict_types=1);

namespace App\Tests\Integration\StateMachine\Entity\Plan\Symfony;

use App\DBAL\PlanStatus;
use App\StateMachine\Entity\Plan\Symfony\SymfonyStatusStateMachine;
use App\Tests\Integration\IntegrationTestCase;

/**
 * @covers \App\StateMachine\Entity\Plan\Symfony\SymfonyStatusStateMachine
 */
class SymfonyStatusStateMachineTest extends IntegrationTestCase
{

    public function test_state_machine_allows_transition_if_it_is_possible() : void
    {
        $plan = $this->schedulerContext->givenPlanExists('plan');

        $result = (new SymfonyStatusStateMachine(
            $this->schedulerContext->getPlanStatusStateMachine(),
            $this->schedulerContext->getPlanRepository()
        ))->can($plan->getId(), 'locking');

        $this->assertTrue($result);
    }

    public function test_state_machine_does_not_allow_transition_if_it_is_not_possible() : void
    {
        $plan = $this->schedulerContext->givenPlanExists('plan');

        $result = (new SymfonyStatusStateMachine(
            $this->schedulerContext->getPlanStatusStateMachine(),
            $this->schedulerContext->getPlanRepository()
        ))->can($plan->getId(), 'event_filling_starting');

        $this->assertFalse($result);
    }

    public function test_state_machine_persists_state() : void
    {
        $plan = $this->schedulerContext->givenPlanExists('plan');

        (new SymfonyStatusStateMachine(
            $this->schedulerContext->getPlanStatusStateMachine(),
            $this->schedulerContext->getPlanRepository()
        ))->apply($plan->getId(), 'locking');

        $this->schedulerContext->getEntityManager()->clear();

        $this->assertEquals(
            PlanStatus::PLAN_STATUS_LOCKED,
            $this->schedulerContext->getPlanRepository()->findOneBy(['id' => $plan->getId()])->getStatus()
        );
    }
}
