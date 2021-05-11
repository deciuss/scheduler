<?php


namespace app\Handler;

use app\Message\Message;

interface Handler
{
    public function __invoke(Message $message);
}