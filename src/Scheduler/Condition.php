<?php

declare(strict_types=1);

namespace App\Scheduler;

interface Condition
{
    public function check($item1, $item2) : bool;
}