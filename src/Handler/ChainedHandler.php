<?php


namespace app\Handler;


use app\Message\Message;


abstract class ChainedHandler implements Handler
{
    private Handler $next;

    abstract protected function canHandle(Message $message) : bool;

    public function setNext(Handler $handler)
    {
        $this->next = $handler;
    }

    public function invokeNext(Message $message)
    {
        $this->next($message);
    }
}