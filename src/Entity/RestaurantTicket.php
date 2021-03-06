<?php

namespace App\Entity;

use App\Repository\RestaurantTicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RestaurantTicketRepository::class)
 */
class RestaurantTicket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ticket_creation", "restaurantTicket_details"})
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     * @Groups({"ticket_creation", "restaurantTicket_details"})
     */
    private $reservationTime;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"ticket_creation", "restaurantTicket_details"})
     */
    private $numberPlace;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="restaurantTickets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("restaurantTicket_details")
     */
    private $restaurant;

    /**
     * @ORM\OneToOne(targetEntity=Ticket::class, inversedBy="restaurantTicket", cascade={"persist", "remove"})
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

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

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
