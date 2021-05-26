<?php

declare(strict_types=1);

namespace App\StateMachine\Entity\Plan;

interface PlanStatusStateMachine
{
    public function can(int $planId, string $transitionName) : bool;
    public function apply(int $planId, string $transitionName) : void;
    public function is(int $planId, string $state) : bool;
}
