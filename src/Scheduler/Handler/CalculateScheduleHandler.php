<?php

declare(strict_types=1);

namespace App\Scheduler\Handler;

use App\Scheduler\Handler\CalculateScheduleChain\ResultImportHandler;
use App\Scheduler\Message\CalculateSchedule;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CalculateScheduleHandler implements MessageHandlerInterface
{
    public function __construct(
        private ResultImportHandler $resultImportHandler
    ) {}

    public function __invoke(CalculateSchedule $message)
    {
        $this->resultImportHandler->executeHandler($message);
    }
}