<?php

declare(strict_types=1);

namespace App\Scheduler\Count;

use App\Entity\Plan;

interface ReportReader
{
    public function getReportForPlan(Plan $plan) : Report;
}
