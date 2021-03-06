<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Plan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @param Plan $plan
     *
     * @return Event[]
     */
    public function findByPlanIdOrderByIdAsc(int $planId): array
    {
        return $this->createQueryBuilder('e1')
            ->innerJoin('e1.subject', 's1', Join::WITH)
            ->innerJoin('s1.plan', 'p1', Join::WITH)
            ->andWhere('p1.id = :plan_id')
            ->setParameter('plan_id', $planId)
            ->orderBy('e1.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countByPlanId(int $planId): int
    {
        return (int) $this->createQueryBuilder('e1')
            ->select('count(e1.id)')
            ->innerJoin('e1.subject', 's1', Join::WITH)
            ->innerJoin('s1.plan', 'p1', Join::WITH)
            ->andWhere('p1.id = :plan_id')
            ->setParameter('plan_id', $planId)
            ->orderBy('e1.id', 'ASC')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findOneByPlanAndMapId(int $planId, int $mapId): Event
    {
        return $this->createQueryBuilder('e1')
            ->innerJoin('e1.subject', 's1', Join::WITH)
            ->innerJoin('s1.plan', 'p1', Join::WITH)
            ->andWhere('p1.id = :plan_id')
            ->andWhere('e1.map_id = :mapId')
            ->setParameter('plan_id', $planId)
            ->setParameter('mapId', $mapId)
            ->getQuery()
            ->getSingleResult();
    }
}
