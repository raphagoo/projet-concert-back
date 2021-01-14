<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RestaurantRepository::class)
 */
class Restaurant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\OneToOne(targetEntity=Salle::class, inversedBy="restaurant", cascade={"persist", "remove"})
     */
    private $salle;

    /**
     * @ORM\OneToMany(targetEntity=RestaurantTicket::class, mappedBy="restaurant")
     */
    private $restaurantTickets;

    public function __construct()
    {
        $this->restaurantTickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    /**
     * @return Collection|RestaurantTicket[]
     */
    public function getRestaurantTickets(): Collection
    {
        return $this->restaurantTickets;
    }

    public function addRestaurantTicket(RestaurantTicket $restaurantTicket): self
    {
        if (!$this->restaurantTickets->contains($restaurantTicket)) {
            $this->restaurantTickets[] = $restaurantTicket;
            $restaurantTicket->setRestaurant($this);
        }

        return $this;
    }

    public function removeRestaurantTicket(RestaurantTicket $restaurantTicket): self
    {
        if ($this->restaurantTickets->removeElement($restaurantTicket)) {
            // set the owning side to null (unless already changed)
            if ($restaurantTicket->getRestaurant() === $this) {
                $restaurantTicket->setRestaurant(null);
            }
        }

        return $this;
    }
}
