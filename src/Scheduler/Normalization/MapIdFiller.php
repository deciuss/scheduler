<?php


namespace App\Scheduler\Normalization;


use App\Entity\Plan;

interface MapIdFiller
{
    public function __invoke(Plan $plan) : void;
}