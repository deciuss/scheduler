<?php

declare(strict_types=1);

namespace App\Scheduler\Count\Calculator;

use App\Entity\Plan;
use App\Scheduler\Count\Report;
use App\Scheduler\Count\ReportReader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class CalculatorReportReader implements ReportReader
{

    private string $calculatorOutputPath;

    public function __construct(
        private DecoderInterface $decoder,
        ParameterBagInterface $parameterBag
    ) {
        $this->calculatorOutputPath = $parameterBag->get('scheduler.calculator.output_path');
    }

    public function getReportForPlan(Plan $plan): Report
    {
        $reportPathName = sprintf("%s/%d.report", $this->calculatorOutputPath, $plan->getId());

        if (! file_exists($reportPathName)) {
            return new CalculatorReport($plan->getStatus());
        }

        $reportArray = $this->decoder->decode(file_get_contents($reportPathName),'csv')[0];

        return new CalculatorReport(
            $plan->getStatus(),
            new \DateTimeImmutable($reportArray['date_time']),
            (int) $reportArray['generation_number'],
            (int) $reportArray['overall_best_hard'],
            (int) $reportArray['overall_best_soft'],
            (int) $reportArray['current_best_hard'],
            (int) $reportArray['current_best_soft'],
            (float) $reportArray['step_current_factor']
        );
    }
}