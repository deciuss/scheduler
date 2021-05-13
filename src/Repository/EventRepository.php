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
     * @return Event[]
     */
    public function findByPlanOrderByIdAsc(Plan $plan) : array
    {
        return $this->createQueryBuilder('e1')
            ->innerJoin('e1.subject', 's1', Join::WITH)
            ->innerJoin('s1.plan', 'p1', Join::WITH)
            ->andWhere('p1 = :plan')
            ->setParameter('plan', $plan)
            ->orderBy('e1.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countByPlan(Plan $plan) : int
    {
        return (int) $this->createQueryBuilder('e1')
            ->select('count(e1.id)')
            ->innerJoin('e1.subject', 's1', Join::WITH)
            ->innerJoin('s1.plan', 'p1', Join::WITH)
            ->andWhere('p1 = :plan')
            ->setParameter('plan', $plan)
            ->orderBy('e1.id', 'ASC')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
