<?php

namespace App\StateMachine\Entity\Plan\Symfony;

use App\StateMachine\Entity\Plan\StatusStateMachine;
use App\Repository\PlanRepository;
use Symfony\Component\Workflow\WorkflowInterface;

class SymfonyStatusStateMachine implements StatusStateMachine
{

    public function __construct(
        private WorkflowInterface $planStatusStateMachine,
        private PlanRepository $planRepository
    ){}

    public function can(int $planId, string $transitionName) : bool
    {
        return $this->planStatusStateMachine->can(
            $this->planRepository->findOneBy(['id' => $planId]),
            $transitionName
        );
    }

    public function apply(int $planId, string $transitionName): void
    {
        $this->planStatusStateMachine->apply(
            $this->planRepository->findOneBy(['id' => $planId]),
            $transitionName
        );
    }
}
