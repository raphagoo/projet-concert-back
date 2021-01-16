<?php


namespace App\BL;


use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;

class TicketManager
{
    /**
     * TicketManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return Ticket[]
     */
    public function getTickets(){
        return $this->em->getRepository(Ticket::class)->findAll();
    }

    /**
     * @param $idTicket
     * @return Ticket|null
     */
    public function findTicketById($idTicket){
        return $this->em->getRepository(Ticket::class)->find($idTicket);
    }

    /**
     * @param Ticket $ticket
     * @return int|null
     */
    public function save(Ticket $ticket){

        $this->em->persist($ticket);
        $this->em->flush();
        return $ticket->getId();
    }

    /**
     * @param $ticket
     */
    public function deleteTicket($ticket)
    {
        $this->em->remove($ticket);
        $this->em->flush();
    }
}
