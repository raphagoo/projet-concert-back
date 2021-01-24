<?php


namespace App\BL;


use App\Entity\RestaurantTicket;
use Doctrine\ORM\EntityManagerInterface;

class RestaurantTicketManager
{
    /**
     * RestaurantTicketManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return RestaurantTicket[]
     */
    public function getRestaurantTickets(){
        return $this->em->getRepository(RestaurantTicket::class)->findAll();
    }

    /**
     * @param $idRestaurantTicket
     * @return RestaurantTicket|null
     */
    public function findRestaurantTicketById($idRestaurantTicket){
        return $this->em->getRepository(RestaurantTicket::class)->find($idRestaurantTicket);
    }

    /**
     * @param RestaurantTicket $restaurantTicket
     * @return int|null
     */
    public function save(RestaurantTicket $restaurantTicket){

        $this->em->persist($restaurantTicket);
        $this->em->flush();
        return $restaurantTicket->getId();
    }

    /**
     * @param $restaurantTicket
     */
    public function deleteRestaurantTicket($restaurantTicket)
    {
        $this->em->remove($restaurantTicket);
        $this->em->flush();
    }
}
