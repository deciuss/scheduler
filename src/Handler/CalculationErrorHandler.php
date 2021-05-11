<?php


namespace app\Handler;


use app\Message\Message;


class CalculationErrorHandler extends ChainedHandler
{

    public function __construct(NormalisationErrorHandler $normalisationErrorHandler)
    {
        $this->setNext($normalisationErrorHandler);
    }

    public function __invoke(Message $message)
    {
        // TODO: Implement __invoke() method.
    }
}