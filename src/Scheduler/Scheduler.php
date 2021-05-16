<?php

declare(strict_types=1);

namespace App\Scheduler;

use App\Repository\PlanRepository;
use App\Scheduler\Message\CalculateSchedule;
use App\Scheduler\UI\Exception\PlanDoesNotExistException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Scheduler facade
 * @package App\Scheduler
 */
class Scheduler
{
    private MessageBusInterface $messageBus;
    private PlanRepository $planRepository;

    public function __construct(
        MessageBusInterface $messageBus,
        PlanRepository $planRepository
    ) {
        $this->messageBus = $messageBus;
        $this->planRepository = $planRepository;
    }

    public function calculate(int $planId) : void
    {
        if (! $plan = $this->planRepository->findOneBy(['id' => $planId])) {
            throw new PlanDoesNotExistException($planId);
        }

        $this->messageBus->dispatch(new CalculateSchedule($plan));
    }

}