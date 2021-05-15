<?php

declare(strict_types=1);

namespace App\Scheduler;


interface Generator
{
    public function getMode() : string;
}