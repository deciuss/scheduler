<?php

declare(strict_types=1);

namespace App\ScheduleCalculator;


interface Generator
{
    public function getMode() : string;
}