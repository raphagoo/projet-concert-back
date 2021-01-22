<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cancellationInsurance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $purchaseDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $qrCodeUrl;

    /**
     * @ORM\ManyToOne(targetEntity=TicketObtaining::class, inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     */
    private $obtainingMethod;

    /**
     * @ORM\OneToOne(targetEntity=ParkingTicket::class, mappedBy="ticket", cascade={"persist", "remove"})
     * @MaxDepth(2)
     */
    private $parkingTicket;

    /**
     * @ORM\OneToOne(targetEntity=RestaurantTicket::class, mappedBy="ticket", cascade={"persist", "remove"})
     * @MaxDepth(2)
     */
    private $restaurantTicket;

    /**
     * @ORM\ManyToOne(targetEntity=Reservation::class, inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(2)
     */
    private $reservation;

    /**
     * @ORM\ManyToOne(targetEntity=Seat::class, inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     */
    private $seat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCancellationInsurance(): ?bool
    {
        return $this->cancellationInsurance;
    }

    public function setCancellationInsurance(bool $cancellationInsurance): self
    {
        $this->cancellationInsurance = $cancellationInsurance;

        return $this;
    }

    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(\DateTimeInterface $purchaseDate): self
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    public function getQrCodeUrl(): ?string
    {
        return $this->qrCodeUrl;
    }

    public function setQrCodeUrl(string $qrCodeUrl): self
    {
        $this->qrCodeUrl = $qrCodeUrl;

        return $this;
    }

    public function getObtainingMethod(): ?TicketObtaining
    {
        return $this->obtainingMethod;
    }

    public function setObtainingMethod(?TicketObtaining $obtainingMethod): self
    {
        $this->obtainingMethod = $obtainingMethod;

        return $this;
    }

    public function getParkingTicket(): ?ParkingTicket
    {
        return $this->parkingTicket;
    }

    public function setParkingTicket(ParkingTicket $parkingTicket): self
    {
        // set the owning side of the relation if necessary
        if ($parkingTicket->getTicket() !== $this) {
            $parkingTicket->setTicket($this);
        }

        $this->parkingTicket = $parkingTicket;

        return $this;
    }

    public function getRestaurantTicket(): ?RestaurantTicket
    {
        return $this->restaurantTicket;
    }

    public function setRestaurantTicket(RestaurantTicket $restaurantTicket): self
    {
        // set the owning side of the relation if necessary
        if ($restaurantTicket->getTicket() !== $this) {
            $restaurantTicket->setTicket($this);
        }

        $this->restaurantTicket = $restaurantTicket;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function getSeat(): ?Seat
    {
        return $this->seat;
    }

    public function setSeat(?Seat $seat): self
    {
        $this->seat = $seat;

        return $this;
    }
}
