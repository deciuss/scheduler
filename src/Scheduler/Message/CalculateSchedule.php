<?php

declare(strict_types=1);

namespace App\Scheduler\Message;

use App\Entity\Plan;
use App\Scheduler\Message;

class CalculateSchedule implements Message
{
    private int $planId;

    public function __construct(Plan $plan)
    {
        $this->planId = $plan->getId();
    }

    public function getPlanId(): int
    {
        return $this->planId;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
