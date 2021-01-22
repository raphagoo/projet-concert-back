<?php


namespace App\BL;


use App\Entity\TicketObtaining;
use Doctrine\ORM\EntityManagerInterface;

class TicketObtainingManager
{
    /**
     * TicketObtainingManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return TicketObtaining[]
     */
    public function getTicketObtainings(){
        return $this->em->getRepository(TicketObtaining::class)->findAll();
    }

    /**
     * @param $idTicketObtaining
     * @return TicketObtaining|null
     */
    public function findTicketObtainingById($idTicketObtaining){
        return $this->em->getRepository(TicketObtaining::class)->find($idTicketObtaining);
    }

    /**
     * @param TicketObtaining $ticketObtaining
     * @return int|null
     */
    public function save(TicketObtaining $ticketObtaining){

        $this->em->persist($ticketObtaining);
        $this->em->flush();
        return $ticketObtaining->getId();
    }

    /**
     * @param $ticketObtaining
     */
    public function deleteTicketObtaining($ticketObtaining)
    {
        $this->em->remove($ticketObtaining);
        $this->em->flush();
    }
}
