<?php


namespace App\ChainHandler;


use App\Message\Message;
use Psr\Log\LoggerInterface;

abstract class ChainHandler
{
    private ?ChainHandler $nextHandler;
    protected LoggerInterface $logger;

    public function __construct(?ChainHandler $nextHandler, LoggerInterface $logger)
    {
        $this->nextHandler = $nextHandler;
        $this->logger = $logger;
    }

    abstract protected function canHandle(Message $message) : bool;

    abstract protected function handle(Message $message) : void;

    protected function preHandle(Message $message) : void
    {
        $this->logger->info(
            sprintf(
                '%s started handling message: %s %s',
                get_class($this), get_class($message),
                json_encode($message)
            )
        );
    }

    protected function postHandle(Message $message) : void
    {
        $this->logger->info(
            sprintf(
                '%s finished handling message: %s %s',
                get_class($this), get_class($message),
                json_encode($message)
            )
        );
    }

    final protected function executeHandler(Message $message) : void
    {
        if (! $this->canHandle($message)) {
            $this->executeNextHandler($message);
            return;
        }

        $this->preHandle($message);
        $this->handle($message);
        $this->postHandle($message);
    }

    private function executeNextHandler(Message $message) : void
    {
        if ($this->nextHandler === null) {
            throw new ChainEndException(get_class($this));
        }

        $this->nextHandler->executeHandler($message);
    }

}
