<?php


namespace App\ScheduleCalculator;


interface Condition
{
    public function check($item1, $item2) : bool;
}