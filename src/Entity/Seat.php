<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=SeatRepository::class)
 */
class Seat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ticket_creation", "concert_details"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"ticket_creation", "concert_details"})
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ticket_creation", "concert_details"})
     */
    private $letter;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"ticket_creation", "concert_details"})
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity=Concert::class, inversedBy="seats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $concert;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="seat")
     * @Groups({"concert_details"})
     */
    private $tickets;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"ticket_creation", "concert_details"})
     */
    private $price;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getLetter(): ?string
    {
        return $this->letter;
    }

    public function setLetter(string $letter): self
    {
        $this->letter = $letter;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getConcert(): ?Concert
    {
        return $this->concert;
    }

    public function setConcert(?Concert $concert): self
    {
        $this->concert = $concert;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setSeat($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getSeat() === $this) {
                $ticket->setSeat(null);
            }
        }

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

}
