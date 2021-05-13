<?php

declare(strict_types=1);

namespace App\ScheduleCalculator;


interface Condition
{

    public function check(CalculatorMapping $item1, CalculatorMapping $item2) : bool;

}