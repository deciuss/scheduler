<?php


namespace App\Message;


class CalculateSchedule implements Message
{
    private int $planId;

    public function __construct(int $planId)
    {
        $this->planId = $planId;
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