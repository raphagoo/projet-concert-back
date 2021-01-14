<?php

namespace App\Entity;

use App\Repository\ParkingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParkingRepository::class)
 */
class Parking
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
     * @ORM\OneToOne(targetEntity=Salle::class, inversedBy="parking", cascade={"persist", "remove"})
     */
    private $salle;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=ParkingTicket::class, mappedBy="parking")
     */
    private $parkingTickets;

    public function __construct()
    {
        $this->parkingTickets = new ArrayCollection();
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

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

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

    /**
     * @return Collection|ParkingTicket[]
     */
    public function getParkingTickets(): Collection
    {
        return $this->parkingTickets;
    }

    public function addParkingTicket(ParkingTicket $parkingTicket): self
    {
        if (!$this->parkingTickets->contains($parkingTicket)) {
            $this->parkingTickets[] = $parkingTicket;
            $parkingTicket->setParking($this);
        }

        return $this;
    }

    public function removeParkingTicket(ParkingTicket $parkingTicket): self
    {
        if ($this->parkingTickets->removeElement($parkingTicket)) {
            // set the owning side to null (unless already changed)
            if ($parkingTicket->getParking() === $this) {
                $parkingTicket->setParking(null);
            }
        }

        return $this;
    }
}
