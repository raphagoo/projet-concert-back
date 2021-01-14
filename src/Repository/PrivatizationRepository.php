<?php

namespace App\Repository;

use App\Entity\Privatization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Privatization|null find($id, $lockMode = null, $lockVersion = null)
 * @method Privatization|null findOneBy(array $criteria, array $orderBy = null)
 * @method Privatization[]    findAll()
 * @method Privatization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrivatizationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Privatization::class);
    }

    // /**
    //  * @return Privatization[] Returns an array of Privatization objects
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
    public function findOneBySomeField($value): ?Privatization
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
