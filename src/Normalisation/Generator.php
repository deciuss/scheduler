<?php


namespace App\Normalisation;


use App\Entity\Plan;

interface Generator
{
    public function generate(Plan $plan) : array;
    public function getMode() : string;
}