<?php


namespace App\BL;


use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;

class EventManager
{
    /**
     * EventManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return Event[]
     */
    public function getEvents(){
        return $this->em->getRepository(Event::class)->findAll();
    }

    /**
     * @param $idEvent
     * @return Event|null
     */
    public function findEventById($idEvent){
        return $this->em->getRepository(Event::class)->find($idEvent);
    }

    public function getLatestEvent(){
        return $this->em->getRepository(Event::class)->findBy(array(),array('id'=>'DESC'),1,0);
    }

    /**
     * @param Event $event
     * @return int|null
     */
    public function save(Event $event){

        $this->em->persist($event);
        $this->em->flush();
        return $event->getId();
    }

    /**
     * @param $event
     */
    public function deleteEvent($event)
    {
        $this->em->remove($event);
        $this->em->flush();
    }
}
