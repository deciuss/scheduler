<?php

declare(strict_types=1);

namespace App\Scheduler;

use App\Repository\PlanRepository;
use App\Scheduler\Count\Report;
use App\Scheduler\Count\ReportReader;
use App\Scheduler\Message\CalculateSchedule;
use App\Scheduler\UI\Exception\PlanDoesNotExistException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Scheduler facade.
 */
class Scheduler
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private PlanRepository $planRepository,
        private ReportReader $reportReader
    ) {
    }

    public function generate(int $planId): void
    {
        if (!$plan = $this->planRepository->findOneBy(['id' => $planId])) {
            throw new PlanDoesNotExistException($planId);
        }

        $this->messageBus->dispatch(new CalculateSchedule($plan));
    }

    public function getReportForPlan(int $planId): Report
    {
        if (!$plan = $this->planRepository->findOneBy(['id' => $planId])) {
            throw new PlanDoesNotExistException($planId);
        }

        return $this->reportReader->getReportForPlan($plan);
    }
}
