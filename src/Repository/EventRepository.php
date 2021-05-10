<?php

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

    public function getCalculatorMapId(Event $event) : int
    {
        return $this->createQueryBuilder('e')
            ->select('count(e.id)')
            ->innerJoin('e.subject', 's', Join::WITH)
            ->innerJoin('s.plan', 'p', Join::WITH)
            ->andWhere('p = :plan')
            ->andWhere('e.id < :id')
            ->setParameter('plan', $event->getSubject()->getPlan())
            ->setParameter('id', $event->getId())
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findOneByCalculatorMapId(Plan $plan, int $calculatorMapId)
    {

        $expr = $this->getEntityManager()->getExpressionBuilder();

        return $this->createQueryBuilder('e1')
            ->innerJoin('e1.subject', 's1', Join::WITH)
            ->innerJoin('s1.plan', 'p1', Join::WITH)
            ->andWhere('p1 = :plan')
            ->andWhere(
                $expr->in(
                    ':calculatorMapId',
                    $this->createQueryBuilder('e2')
                        ->select('count(e2.id)')
                        ->innerJoin('e2.subject', 's2', Join::WITH)
                        ->innerJoin('s2.plan', 'p2', Join::WITH)
                        ->andWhere('p2 = :plan')
                        ->andWhere('e2.id < e1.id')
                        ->getDQL()
                )
            )
            ->setParameter('plan', $plan)
            ->setParameter('calculatorMapId', $calculatorMapId)
            ->getQuery()
            ->getSingleResult()
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
