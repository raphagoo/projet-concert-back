<?php

namespace App\Repository;

use App\Entity\RestaurantTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantTicket[]    findAll()
 * @method RestaurantTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantTicket::class);
    }

    // /**
    //  * @return RestaurantTicket[] Returns an array of RestaurantTicket objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RestaurantTicket
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
