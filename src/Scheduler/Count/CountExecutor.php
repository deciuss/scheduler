<?php

declare(strict_types=1);

namespace App\Scheduler\Count;

interface CountExecutor
{
    public function __invoke(int $planId): void;
}
