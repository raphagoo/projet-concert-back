<?php


namespace App\BL;


use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;

class ReservationManager
{
    /**
     * ReservationManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return Reservation[]
     */
    public function getReservations(){
        return $this->em->getRepository(Reservation::class)->findAll();
    }

    /**
     * @param $idReservation
     * @return Reservation|null
     */
    public function findReservationById($idReservation){
        return $this->em->getRepository(Reservation::class)->find($idReservation);
    }

    /**
     * @param Reservation $reservation
     * @return Reservation
     */
    public function save(Reservation $reservation){

        $this->em->persist($reservation);
        $this->em->flush();
        return $reservation;
    }

    /**
     * @param $reservation
     */
    public function deleteReservation($reservation)
    {
        $this->em->remove($reservation);
        $this->em->flush();
    }
}
