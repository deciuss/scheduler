<?php

declare(strict_types=1);

namespace App\Scheduler\UI\Exception;

use Throwable;

class PlanDoesNotExistException extends \RuntimeException
{
    public function __construct(int $planId, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Plan with id: %d does not exist.', $planId), $code, $previous);
    }
}
