<?php

namespace App\Scheduler\Normalization;

interface WriterFactory
{
    public function create(string $dataIdentifier) : Writer;
}
