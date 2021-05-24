<?php

declare(strict_types=1);

namespace App\Scheduler\Handler\CalculateScheduleChain\LockHandler;

use App\DBAL\PlanStatus;
use App\Scheduler\Handler\ChainHandlerAbstract;
use App\Scheduler\Message\CalculateSchedule;
use App\Scheduler\Message;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Scheduler\Handler\CalculateScheduleChain\LockHandler as LockHandlerInterface;
use App\Scheduler\Handler\CalculateScheduleChain\InProgressHandler as InProgressHandlerInterface;

class DefaultLockHandler extends ChainHandlerAbstract implements LockHandlerInterface
{
    private MessageBusInterface $messageBus;
    private EntityManagerInterface $entityManager;
    private PlanRepository $planRepository;

    public function canHandle(Message $message): bool
    {
        return
            $message instanceof CalculateSchedule
            && in_array(
                $this->planRepository->findOneBy(['id' => $message->getPlanId()])->getStatus(),
                [
                    PlanStatus::PLAN_STATUS_UNDER_CONSTRUCTION
                ]
        );
    }

    public function __construct(
        InProgressHandlerInterface $inProgressHandler,
        LoggerInterface $logger,
        MessageBusInterface $messageBus,
        EntityManagerInterface $entityManager,
        PlanRepository $planRepository
    ) {
        parent::__construct($inProgressHandler, $logger);
        $this->messageBus = $messageBus;
        $this->entityManager = $entityManager;
        $this->planRepository = $planRepository;
    }

    public function handle(Message $message) : void
    {
        $plan = $this->planRepository->findOneBy(['id' => $message->getPlanId()]);

        $plan->setStatus(PlanStatus::PLAN_STATUS_LOCKED);
        $this->entityManager->flush();

        $this->messageBus->dispatch($message);
    }
}