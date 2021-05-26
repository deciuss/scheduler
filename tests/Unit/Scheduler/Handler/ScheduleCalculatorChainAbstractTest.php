<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Handler;

use App\StateMachine\Entity\Plan\PlanStatusStateMachine;
use PHPUnit\Framework\TestCase;

abstract class ScheduleCalculatorChainAbstractTest extends TestCase
{
    protected function createPlanStatusStateMachineMock(
        int $planId,
        string $firstTransition,
        string $secondTransition
    ) : PlanStatusStateMachine
    {
        $planStatusStateMachineMock = $this->getMockBuilder(PlanStatusStateMachine::class)->getMock();
        $planStatusStateMachineMock->expects($this->once())->method("can")->with($planId, $firstTransition)->willReturn(true);
        $planStatusStateMachineMock->expects($this->exactly(2))->method("apply")->withConsecutive(
            [$planId, $firstTransition],
            [$planId, $secondTransition]
        );

        return $planStatusStateMachineMock;
    }
}
