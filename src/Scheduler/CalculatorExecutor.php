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

    public function __construct(
        ParameterBagInterface $parameterBag
    ) {
        $this->calculatorBinPathname = $parameterBag->get('scheduler.calculator.bin_pathname');
        $this->calculatorDataPath = $parameterBag->get('scheduler.calculator.data_path');
        $this->calculatorOutputPath = $parameterBag->get('scheduler.calculator.output_path');
        $this->calculatorMaxIterationNumber = $parameterBag->get('scheduler.calculator.max_iteration_number');
    }

    public function __invoke(int $planId) : void
    {
        ini_set('max_execution_time', '600');

        $process = new Process([
            $this->calculatorBinPathname,
            sprintf("%s/%d", $this->calculatorDataPath, $planId),
            sprintf("%s/%d", $this->calculatorOutputPath, $planId),
            $this->calculatorMaxIterationNumber
        ]);

        $process->setTimeout(600.0);

        switch ($exitCode = $process->run()) {
            case 0:
                return;
            case 13:
                throw new FeasibleSolutionNotFoundException($planId);
            default:
                throw new \RuntimeException(sprintf("Calculation for plan %d failed. Exit code: %d", $planId, $exitCode));
        }

    }
}