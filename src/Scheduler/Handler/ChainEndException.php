<?php

declare(strict_types=1);

namespace App\Scheduler\Handler;

use Throwable;

class ChainEndException extends \RuntimeException
{
    public function __construct($lastHandlerName, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf("Handler Chain ended without being resolved. Last Handler: %s", $lastHandlerName),
            $code,
            $previous
        );
    }
}