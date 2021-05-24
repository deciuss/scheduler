<?php

declare(strict_types=1);

namespace App\Scheduler;

use App\Scheduler\Exception\FeasibleSolutionNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Process\Process;

class CalculatorExecutor
{
    private string $calculatorBinPathname;
    private string $calculatorDataPath;
    private string $calculatorOutputPath;
    private string $calculatorMaxIterationNumber;
    private string $calculatorMaxExecutionTimeSeconds;

    public function __construct(
        ParameterBagInterface $parameterBag
    ) {
        $this->calculatorBinPathname = $parameterBag->get('scheduler.calculator.bin_pathname');
        $this->calculatorDataPath = $parameterBag->get('scheduler.calculator.data_path');
        $this->calculatorOutputPath = $parameterBag->get('scheduler.calculator.output_path');
        $this->calculatorMaxIterationNumber = $parameterBag->get('scheduler.calculator.max_iteration_number');
        $this->calculatorMaxExecutionTimeSeconds = $parameterBag->get('scheduler.calculator.max_execution_time_seconds');
    }

    public function __invoke(string $filename) : void
    {
        $process = new Process([
            $this->calculatorBinPathname,
            sprintf("%s/%s", $this->calculatorDataPath, $filename),
            sprintf("%s/%s", $this->calculatorOutputPath, $filename),
            $this->calculatorMaxIterationNumber
        ]);

        $process->setTimeout((float) $this->calculatorMaxExecutionTimeSeconds);

        switch ($exitCode = $process->run()) {
            case 0:
                return;
            case 13:
                throw new FeasibleSolutionNotFoundException($filename);
            default:
                throw new \RuntimeException(sprintf("Calculation for plan file %s failed. Exit code: %d", $filename, $exitCode));
        }
    }

}