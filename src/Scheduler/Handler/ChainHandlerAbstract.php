<?php

declare(strict_types=1);

namespace App\Scheduler\Handler;

use App\Scheduler\Message;
use Psr\Log\LoggerInterface;

abstract class ChainHandlerAbstract implements ChainHandler
{
    private ?ChainHandler $nextHandler;
    protected LoggerInterface $logger;

    public function __construct(?ChainHandler $nextHandler, LoggerInterface $logger)
    {
        $this->nextHandler = $nextHandler;
        $this->logger = $logger;
    }

    protected function preHandle(Message $message): void
    {
        $this->logger->info(
            sprintf(
                '%s started handling message: %s %s',
                get_class($this), get_class($message),
                json_encode($message)
            )
        );
    }

    protected function postHandle(Message $message): void
    {
        $this->logger->info(
            sprintf(
                '%s finished handling message: %s %s',
                get_class($this), get_class($message),
                json_encode($message)
            )
        );
    }

    final public function executeHandler(Message $message): void
    {
        if (!$this->canHandle($message)) {
            $this->executeNextHandler($message);

            return;
        }

        $this->preHandle($message);
        $this->handle($message);
        $this->postHandle($message);
    }

    private function executeNextHandler(Message $message): void
    {
        if (null === $this->nextHandler) {
            throw new ChainEndException(get_class($this));
        }

        $this->nextHandler->executeHandler($message);
    }
}
