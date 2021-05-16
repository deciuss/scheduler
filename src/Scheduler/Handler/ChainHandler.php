<?php

declare(strict_types=1);

namespace App\Scheduler\Handler;

use App\Scheduler\Message;

interface ChainHandler
{
    public function canHandle(Message $message) : bool;
    public function handle(Message $message) : void;
}
