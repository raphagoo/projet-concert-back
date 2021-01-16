<?php


namespace App\BL;


use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;

class RestaurantManager
{
    /**
     * RestaurantManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return Restaurant[]
     */
    public function getRestaurants(){
        return $this->em->getRepository(Restaurant::class)->findAll();
    }

    /**
     * @param $idRestaurant
     * @return Restaurant|null
     */
    public function findRestaurantById($idRestaurant){
        return $this->em->getRepository(Restaurant::class)->find($idRestaurant);
    }

    /**
     * @param Restaurant $restaurant
     * @return int|null
     */
    public function save(Restaurant $restaurant){

        $this->em->persist($restaurant);
        $this->em->flush();
        return $restaurant->getId();
    }

    /**
     * @param $restaurant
     */
    public function deleteRestaurant($restaurant)
    {
        $this->em->remove($restaurant);
        $this->em->flush();
    }
}
