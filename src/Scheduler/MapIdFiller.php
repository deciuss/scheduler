<?php


namespace App\Scheduler;


use App\Entity\Plan;

interface MapIdFiller
{
    public function __invoke(Plan $plan) : void;
}