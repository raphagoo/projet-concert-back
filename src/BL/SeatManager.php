<?php


namespace App\BL;


use App\Entity\Seat;
use Doctrine\ORM\EntityManagerInterface;

class SeatManager
{
    /**
     * SeatManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return Seat[]
     */
    public function getSeats(){
        return $this->em->getRepository(Seat::class)->findAll();
    }

    /**
     * @param $idSeat
     * @return Seat|null
     */
    public function findSeatById($idSeat){
        return $this->em->getRepository(Seat::class)->find($idSeat);
    }

    /**
     * @param Seat $seat
     * @return int|null
     */
    public function save(Seat $seat){

        $this->em->persist($seat);
        $this->em->flush();
        return $seat->getId();
    }

    /**
     * @param $seat
     */
    public function deleteSeat($seat)
    {
        $this->em->remove($seat);
        $this->em->flush();
    }
}
