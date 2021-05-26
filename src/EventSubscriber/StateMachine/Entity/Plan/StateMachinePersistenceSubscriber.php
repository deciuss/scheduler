<?php

declare(strict_types=1);

namespace App\EventSubscriber\StateMachine\Entity\Plan;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StateMachinePersistenceSubscriber implements EventSubscriberInterface
{

    public function __construct(
       private EntityManagerInterface $entityManager
    ) {}

    public function onEnter()
    {
        $this->entityManager->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.plan_status.entered' => 'onEnter',
        ];
    }
}