<?php


namespace app\Handler;


use app\Message\ScheduleCalculationMessage;
use app\Message\Message;


class NormalisationErrorHandler implements Handler
{

    public function __invoke(Message $message)
    {
        assert($message instanceof ScheduleCalculationMessage, "Invalid message type.");
        throw new \RuntimeException(sprintf("Normalisation for plan %d failed.", $message->getPlanId()));
    }
}