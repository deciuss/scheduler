<?php

declare(strict_types=1);

namespace App\Scheduler\Exception;

use Throwable;

class FeasibleSolutionNotFoundException extends \RuntimeException
{
    public function __construct(string $filename, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf("Solution for data file: %s not found.", $filename), $code, $previous);
    }
}