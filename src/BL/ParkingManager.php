<?php


namespace App\BL;


use App\Entity\Parking;
use Doctrine\ORM\EntityManagerInterface;

class ParkingManager
{
    /**
     * ParkingManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return Parking[]
     */
    public function getParkings(){
        return $this->em->getRepository(Parking::class)->findAll();
    }

    /**
     * @param $idParking
     * @return Parking|null
     */
    public function findParkingById($idParking){
        return $this->em->getRepository(Parking::class)->find($idParking);
    }

    /**
     * @param Parking $parking
     * @return int|null
     */
    public function save(Parking $parking){

        $this->em->persist($parking);
        $this->em->flush();
        return $parking->getId();
    }

    /**
     * @param $parking
     */
    public function deleteParking($parking)
    {
        $this->em->remove($parking);
        $this->em->flush();
    }
}
