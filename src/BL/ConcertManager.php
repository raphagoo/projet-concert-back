<?php


namespace App\BL;


use App\Entity\Concert;
use Doctrine\ORM\EntityManagerInterface;

class ConcertManager
{
    /**
     * ConcertManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return Concert[]
     */
    public function getConcerts(){
        return $this->em->getRepository(Concert::class)->findAll();
    }

    /**
     * @param $idConcert
     * @return Concert|null
     */
    public function findConcertById($idConcert){
        return $this->em->getRepository(Concert::class)->find($idConcert);
    }

    /**
     * @param Concert $concert
     * @return int|null
     */
    public function save(Concert $concert){

        $this->em->persist($concert);
        $this->em->flush();
        return $concert->getId();
    }

    /**
     * @param $concert
     */
    public function deleteConcert($concert)
    {
        $this->em->remove($concert);
        $this->em->flush();
    }
}
