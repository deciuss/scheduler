<?php

namespace App\Scheduler\Normalization;

interface MapIdFiller
{
    public function __invoke(int $planId): void;
}
