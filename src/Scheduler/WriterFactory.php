<?php


namespace App\Scheduler;


interface WriterFactory
{
    public function create(string $dataIdentifier) : Writer;
}