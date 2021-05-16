<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler\Handler;

use App\Entity\Plan;
use PHPUnit\Framework\TestCase;

abstract class ScheduleCalculatorChainAbstractTest extends TestCase
{
    protected function createPlanMockWithStatusAndExpectingStatusChanges(
        string $actualStatus,
        string $firstStatusChange,
        string $secondStatusChange
    ) {
        $planMock = $this->getMockBuilder(Plan::class)->getMock();
        $planMock->method("getId")->willReturn(1);
        $planMock->method("getStatus")->willReturn($actualStatus);
        $planMock->expects($this->exactly(2))->method("setStatus")->withConsecutive(
            [$firstStatusChange],
            [$secondStatusChange]
        );

        return $planMock;
    }
}
