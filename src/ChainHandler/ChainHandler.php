<?php

declare(strict_types=1);

namespace App\ChainHandler;

use App\Message\Message;

interface ChainHandler
{
    public function canHandle(Message $message) : bool;
    public function handle(Message $message) : void;
}
