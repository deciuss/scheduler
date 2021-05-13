<?php


namespace App\ScheduleCalculator;


interface WriterFactory
{
    public function create(string $dataIdentifier) : Writer;
}