<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SalleRepository::class)
 */
class Salle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"event_details", "salle_details", "salle_list", "restaurantTicket_details", "event_search"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"salle_details", "salle_list"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"salle_details", "salle_list", "event_search"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"salle_details", "salle_list"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"salle_details", "salle_list"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"salle_details", "salle_list"})
     */
    private $capacity;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="salle")
     * @Groups("salle_details")
     */
    private $events;

    /**
     * @ORM\OneToOne(targetEntity=Parking::class, mappedBy="salle", cascade={"persist", "remove"})
     * @Groups("salle_details")
     */
    private $parking;

    /**
     * @ORM\OneToOne(targetEntity=Restaurant::class, mappedBy="salle", cascade={"persist", "remove"})
     * @Groups("salle_details")
     */
    private $restaurant;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
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

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setSalle($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getSalle() === $this) {
                $event->setSalle(null);
            }
        }

        return $this;
    }

    public function getParking(): ?Parking
    {
        return $this->parking;
    }

    public function setParking(?Parking $parking): self
    {
        // unset the owning side of the relation if necessary
        if ($parking === null && $this->parking !== null) {
            $this->parking->setSalle(null);
        }

        // set the owning side of the relation if necessary
        if ($parking !== null && $parking->getSalle() !== $this) {
            $parking->setSalle($this);
        }

        $this->parking = $parking;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        // unset the owning side of the relation if necessary
        if ($restaurant === null && $this->restaurant !== null) {
            $this->restaurant->setSalle(null);
        }

        // set the owning side of the relation if necessary
        if ($restaurant !== null && $restaurant->getSalle() !== $this) {
            $restaurant->setSalle($this);
        }

        $this->restaurant = $restaurant;

        return $this;
    }
}
