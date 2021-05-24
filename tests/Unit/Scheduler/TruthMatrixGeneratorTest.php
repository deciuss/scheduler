<?php

declare(strict_types=1);

namespace App\Tests\Unit\Scheduler;

use PHPUnit\Framework\TestCase;
use App\Scheduler\Normalization\Condition;
use App\Scheduler\Normalization\TruthMatrixGenerator;

/**
 * @covers \App\Scheduler\Normalization\TruthMatrixGenerator
 * @covers \App\Scheduler\Normalization\Generator\EventRoomFit
 * @covers \App\Scheduler\Normalization\Generator\EventTimeslotShare
 */
class TruthMatrixGeneratorTest extends TestCase
{
    public function test_if_generates_matrix_with_false_values_when_condition_always_false() : void
    {
        $alwaysFalseConditionStub = $this->createStub(\App\Scheduler\Normalization\Condition::class);
        $alwaysFalseConditionStub->method("check")->willReturn(false);

        $actualMatrix = (new \App\Scheduler\Normalization\TruthMatrixGenerator())->generate([1, 2], [3, 4, 5], $alwaysFalseConditionStub);

        $this->assertEquals(
            [
                [false, false, false],
                [false, false, false]
            ],
            $actualMatrix
        );
    }

    public function test_if_generates_matrix_with_true_values_when_condition_always_true() : void
    {
        $alwaysTrueConditionStub = $this->createStub(\App\Scheduler\Normalization\Condition::class);
        $alwaysTrueConditionStub->method("check")->willReturn(true);

        $actualMatrix = (new \App\Scheduler\Normalization\TruthMatrixGenerator())->generate([1, 2], [3, 4, 5], $alwaysTrueConditionStub);

        $this->assertEquals(
            [
                [true, true, true],
                [true, true, true]
            ],
            $actualMatrix
        );
    }

    public function test_if_generates_matrix_with_alternating_values_when_condition_alternating() : void
    {
        $conditionStub = $this->createStub(\App\Scheduler\Normalization\Condition::class);
        $conditionStub->method("check")->willReturn(true, false, false, true);

        $actualMatrix = (new TruthMatrixGenerator())->generate([1, 2], [3, 4], $conditionStub);

        $this->assertEquals(
            [
                [true, false],
                [false, true]
            ],
            $actualMatrix
        );
    }

    public function test_if_generates_matrix_with_conjunction_when_multiple_condition_present() : void
    {
        $conditionStub1 = $this->createStub(\App\Scheduler\Normalization\Condition::class);
        $conditionStub1->method("check")->willReturn(true, false, false, true);

        $conditionStub2 = $this->createStub(\App\Scheduler\Normalization\Condition::class);
        $conditionStub2->method("check")->willReturn(true, false, true, false);

        $actualMatrix = (new \App\Scheduler\Normalization\TruthMatrixGenerator())->generate([1, 2], [3, 4], $conditionStub1, $conditionStub2);

        $this->assertEquals(
            [
                [true, false],
                [false, false]
            ],
            $actualMatrix
        );
    }
}
