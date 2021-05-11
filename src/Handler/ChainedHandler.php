<?php


namespace App\Handler;


use App\Message\Message;
use Psr\Log\LoggerInterface;

abstract class ChainedHandler
{
    private ?ChainedHandler $nextHandler;
    protected LoggerInterface $logger;

    abstract protected function canHandle(Message $message) : bool;

    protected function invokeNextHandler(Message $message) : void
    {
        if ($this->nextHandler === null) {
            throw new ChainEndException(get_class($this));
        }

        return $this->nextHandler($message);
    }

    public function __construct(?ChainedHandler $nextHandler, LoggerInterface $logger)
    {
        $this->nextHandler = $nextHandler;
        $this->logger = $logger;
    }
}