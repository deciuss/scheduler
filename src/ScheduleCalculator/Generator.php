<?php


namespace App\ScheduleCalculator;


use App\Entity\Plan;

interface Generator
{
    public function generate(Plan $plan) : array;
    public function getMode() : string;
}