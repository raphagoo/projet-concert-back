<?php

namespace App\Entity;

use App\Repository\ParkingTicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ParkingTicketRepository::class)
 */
class ParkingTicket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("ticket_creation")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Parking::class, inversedBy="parkingTickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parking;

    /**
     * @ORM\OneToOne(targetEntity=Ticket::class, inversedBy="parkingTicket", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticket;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParking(): ?Parking
    {
        return $this->parking;
    }

    public function setParking(?Parking $parking): self
    {
        $this->parking = $parking;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }
}
