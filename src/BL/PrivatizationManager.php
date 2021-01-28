<?php


namespace App\BL;


use App\Entity\Privatization;
use Doctrine\ORM\EntityManagerInterface;

class PrivatizationManager
{
    /**
     * PrivatizationManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return Privatization[]
     */
    public function getPrivatizations(){
        return $this->em->getRepository(Privatization::class)->findAll();
    }

    /**
     * @param $idPrivatization
     * @return Privatization|null
     */
    public function findPrivatizationById($idPrivatization){
        return $this->em->getRepository(Privatization::class)->find($idPrivatization);
    }

    /**
     * @param Privatization $privatization
     * @return int|null
     */
    public function save(Privatization $privatization){

        $this->em->persist($privatization);
        $this->em->flush();
        return $privatization->getId();
    }

    /**
     * @param $privatization
     */
    public function deletePrivatization($privatization)
    {
        $this->em->remove($privatization);
        $this->em->flush();
    }
}
