<?php

declare(strict_types=1);

namespace App\Scheduler\Exception;

use Throwable;

class FeasibleSolutionNotFoundException extends \RuntimeException
{
    public function __construct(int $planId, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf("Solution for Plan id: %d not found.", $planId), $code, $previous);
    }
}