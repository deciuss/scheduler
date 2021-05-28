<?php

declare(strict_types=1);

namespace App\Tests\Integration\Scheduler;

use App\Scheduler\Calculator\CalculatorCountExecutor;
use App\Scheduler\Exception\FeasibleSolutionNotFoundException;
use App\Tests\Integration\IntegrationTestCase;

/**
 * @covers \App\Scheduler\Calculator\CalculatorCountExecutor
 */
class CalculatorCountExecutorTest extends IntegrationTestCase
{
    public function test_if_generates_output_file_for_trivial_data() : void
    {
        copy(
            "tests/resources/calculator/data/trivial",
            sprintf(
                "%s/%d",
                $this->schedulerContext->getParameterBag()->get('scheduler.calculator.data_path'),
                $trivialPlanId = 7
            )
        );

        (new CalculatorCountExecutor($this->schedulerContext->getParameterBag()))($trivialPlanId);

        $this->assertFileExists(
            sprintf(
                "%s/%d",
                $this->schedulerContext->getParameterBag()->get('scheduler.calculator.output_path'),
                $trivialPlanId
            )
        );
    }

    public function test_if_generates_exception_for_impossible_data() : void
    {
        copy(
            "tests/resources/calculator/data/impossible",
            sprintf(
                "%s/%d",
                $this->schedulerContext->getParameterBag()->get('scheduler.calculator.data_path'),
                $impossiblePlanId = 13
            )
        );

        $this->expectException(FeasibleSolutionNotFoundException::class);

        (new CalculatorCountExecutor($this->schedulerContext->getParameterBag()))($impossiblePlanId);
    }
}
