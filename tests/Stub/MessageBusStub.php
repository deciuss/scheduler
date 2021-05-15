<?php

declare(strict_types=1);

namespace App\Tests\Stub;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class MessageBusStub implements  MessageBusInterface
{
    public function dispatch($message, array $stamps = []) : Envelope
    {
        return new Envelope($message, $stamps);
    }
}