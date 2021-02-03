<?php

namespace App\Repository;

use App\Entity\Concert;
use App\Entity\Seat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Concert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Concert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Concert[]    findAll()
 * @method Concert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConcertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Concert::class);
    }

    public function createSeats(Concert $concert){
        $priceMax = $concert->getPriceMax();
        $percentage = $concert->getPercentage();

        $capacity = $concert->getEvent()->getSalle()->getCapacity();

        $seatsByCategory = floor($capacity / $concert->getCategoryNumber());
        $seatsByLine = floor($seatsByCategory / 3);

        $category = 1;
        for($i = 0; $i < $concert->getCategoryNumber(); $i++) {
            $lineIndex = 0;
            for ($j = 0; $j + $seatsByLine < $seatsByCategory; $j = $j + $seatsByLine) {
                $line = ["A", "B", "C", "D", "E"];
                for ($k = 1; $k < $seatsByLine + 1; $k++) {
                    $seat = new Seat();
                    $seat->setConcert($concert);
                    $seat->setCategory($category);
                    $seat->setLetter($line[$lineIndex]);
                    $seat->setNumber($k);
                    $seat->setPrice($priceMax);
                    try {
                        $this->_em->persist($seat);
                    } catch (ORMException $e) {
                        dd($e);
                    }
                }
                $priceMax = round($priceMax * (100 - $percentage) / 100);
                $lineIndex++;
            }
            $category++;
        }
    }

    // /**
    //  * @return Concert[] Returns an array of Concert objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Concert
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
