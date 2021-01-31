<?php


namespace App\BL;


use App\Entity\ParkingTicket;
use Doctrine\ORM\EntityManagerInterface;

class ParkingTicketManager
{
    /**
     * ParkingTicketManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return ParkingTicket[]
     */
    public function getParkingTickets(){
        return $this->em->getRepository(ParkingTicket::class)->findAll();
    }

    /**
     * @param $idParkingTicket
     * @return ParkingTicket|null
     */
    public function findParkingTicketById($idParkingTicket){
        return $this->em->getRepository(ParkingTicket::class)->find($idParkingTicket);
    }

    /**
     * @param ParkingTicket $parkingTicket
     * @return ParkingTicket
     */
    public function save(ParkingTicket $parkingTicket){

        $this->em->persist($parkingTicket);
        $this->em->flush();
        return $parkingTicket;
    }

    /**
     * @param $parkingTicket
     */
    public function deleteParkingTicket($parkingTicket)
    {
        $this->em->remove($parkingTicket);
        $this->em->flush();
    }
}
