<?php

namespace App\Repository;

use App\Entity\ParkingTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ParkingTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParkingTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParkingTicket[]    findAll()
 * @method ParkingTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParkingTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParkingTicket::class);
    }

    // /**
    //  * @return ParkingTicket[] Returns an array of ParkingTicket objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParkingTicket
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
