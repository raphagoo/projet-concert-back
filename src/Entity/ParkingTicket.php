<?php

namespace App\Entity;

use App\Repository\ParkingTicketRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParkingTicketRepository::class)
 */
class ParkingTicket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $reservationTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberPlace;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modificationDate;

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

    public function getReservationTime(): ?\DateTimeInterface
    {
        return $this->reservationTime;
    }

    public function setReservationTime(\DateTimeInterface $reservationTime): self
    {
        $this->reservationTime = $reservationTime;

        return $this;
    }

    public function getNumberPlace(): ?int
    {
        return $this->numberPlace;
    }

    public function setNumberPlace(int $numberPlace): self
    {
        $this->numberPlace = $numberPlace;

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(\DateTimeInterface $modificationDate): self
    {
        $this->modificationDate = $modificationDate;

        return $this;
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
