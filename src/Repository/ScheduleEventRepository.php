<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ScheduleEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ScheduleEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScheduleEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScheduleEvent[]    findAll()
 * @method ScheduleEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScheduleEvent::class);
    }
}
