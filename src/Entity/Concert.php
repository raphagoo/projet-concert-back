<?php

namespace App\Entity;

use App\Repository\ConcertRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConcertRepository::class)
 */
class Concert
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     */
    private $time;

    /**
     * @ORM\Column(type="time")
     */
    private $openingTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceMax;

    /**
     * @ORM\Column(type="float")
     */
    private $percentage;

    /**
     * @ORM\Column(type="integer")
     */
    private $categoryNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $artistDescription;

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
}
