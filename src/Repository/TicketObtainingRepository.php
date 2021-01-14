<?php

namespace App\Repository;

use App\Entity\TicketObtaining;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TicketObtaining|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketObtaining|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketObtaining[]    findAll()
 * @method TicketObtaining[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketObtainingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketObtaining::class);
    }

    // /**
    //  * @return TicketObtaining[] Returns an array of TicketObtaining objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TicketObtaining
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
