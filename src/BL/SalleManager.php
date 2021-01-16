<?php


namespace App\BL;


use App\Entity\Salle;
use Doctrine\ORM\EntityManagerInterface;

class SalleManager
{
    /**
     * SalleManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return Salle[]
     */
    public function getSalles(){
        return $this->em->getRepository(Salle::class)->findAll();
    }

    /**
     * @param $idSalle
     * @return Salle|null
     */
    public function findSalleById($idSalle){
        return $this->em->getRepository(Salle::class)->find($idSalle);
    }

    /**
     * @param Salle $salle
     * @return int|null
     */
    public function save(Salle $salle){

        $this->em->persist($salle);
        $this->em->flush();
        return $salle->getId();
    }

    /**
     * @param $salle
     */
    public function deleteSalle($salle)
    {
        $this->em->remove($salle);
        $this->em->flush();
    }
}
