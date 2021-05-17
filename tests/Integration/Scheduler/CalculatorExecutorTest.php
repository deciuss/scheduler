<?php

declare(strict_types=1);

namespace App\Tests\Integration\Scheduler;

use App\Scheduler\CalculatorExecutor;
use App\Scheduler\Exception\FeasibleSolutionNotFoundException;
use App\Tests\Integration\IntegrationTestCase;

/**
 * @covers \App\Scheduler\CalculatorExecutor
 */
class CalculatorExecutorTest extends IntegrationTestCase
{
    public function test_if_generates_output_file_for_trivial_data() : void
    {
        copy(
            sprintf(
                "tests/resources/calculator/data/%s",
                $filename = "trivial"
            ),
            sprintf(
                "%s/%s",
                $this->schedulerContext->getParameterBag()->get('scheduler.calculator.data_path'),
                $filename
            )
        );

        (new CalculatorExecutor($this->schedulerContext->getParameterBag()))($filename);

        $this->assertFileExists(
            sprintf(
                "%s/%s",
                $this->schedulerContext->getParameterBag()->get('scheduler.calculator.output_path'),
                $filename
            )
        );
    }

    public function test_if_generates_exception_for_impossible_data() : void
    {
        copy(
            sprintf(
                "tests/resources/calculator/data/%s",
                $filename = "impossible"
            ),
            sprintf(
                "%s/%s",
                $this->schedulerContext->getParameterBag()->get('scheduler.calculator.data_path'),
                $filename
            )
        );

        $this->expectException(FeasibleSolutionNotFoundException::class);

        (new CalculatorExecutor($this->schedulerContext->getParameterBag()))($filename);
    }
}