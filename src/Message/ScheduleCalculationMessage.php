<?php


namespace app\Message;


class ScheduleCalculationMessage implements Message
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
}