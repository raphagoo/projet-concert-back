<?php

namespace App\Entity;

use App\Repository\ConcertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=ConcertRepository::class)
 */
class Concert
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"concert_details", "event_search", "event_details", "concert_list"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"concert_details", "event_search", "event_details", "concert_list"})
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     * @Groups({"concert_details", "event_search", "event_details", "concert_list"})
     */
    private $time;

    /**
     * @ORM\Column(type="time")
     * @Groups({"concert_details", "event_search", "event_details", "concert_list"})
     */
    private $openingTime;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"concert_details", "event_search", "event_details", "concert_list"})
     */
    private $priceMax;

    /**
     * @ORM\Column(type="float")
     * @Groups({"concert_details", "event_details", "concert_list"})
     */
    private $percentage;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"concert_details", "event_details", "concert_list"})
     */
    private $categoryNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"concert_details"})
     */
    private $artistDescription;

    /**
     * @ORM\OneToMany(targetEntity=Seat::class, mappedBy="concert", orphanRemoval=true)
     * @Groups("concert_details")
     */
    private $seats;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="concerts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"concert_details", "concert_list"})
     */
    private $event;

    public function __construct()
    {
        $this->seats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getOpeningTime(): ?\DateTimeInterface
    {
        return $this->openingTime;
    }

    public function setOpeningTime(\DateTimeInterface $openingTime): self
    {
        $this->openingTime = $openingTime;

        return $this;
    }

    public function getPriceMax(): ?int
    {
        return $this->priceMax;
    }

    public function setPriceMax(int $priceMax): self
    {
        $this->priceMax = $priceMax;

        return $this;
    }

    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    public function setPercentage(float $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getCategoryNumber(): ?int
    {
        return $this->categoryNumber;
    }

    public function setCategoryNumber(int $categoryNumber): self
    {
        $this->categoryNumber = $categoryNumber;

        return $this;
    }

    public function getArtistDescription(): ?string
    {
        return $this->artistDescription;
    }

    public function setArtistDescription(?string $artistDescription): self
    {
        $this->artistDescription = $artistDescription;

        return $this;
    }

    /**
     * @return Collection|Seat[]
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(Seat $seat): self
    {
        if (!$this->seats->contains($seat)) {
            $this->seats[] = $seat;
            $seat->setConcert($this);
        }

        return $this;
    }

    public function removeSeat(Seat $seat): self
    {
        if ($this->seats->removeElement($seat)) {
            // set the owning side to null (unless already changed)
            if ($seat->getConcert() === $this) {
                $seat->setConcert(null);
            }
        }

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

}
