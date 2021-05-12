<?php


namespace App\Message;


use App\Entity\Plan;

class CalculateSchedule implements Message
{
    private int $planId;

    public function __construct(Plan $plan)
    {
        $this->planId = $plan->getId();
    }

    public function getPlanId()
    {
        return $this->planId;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}