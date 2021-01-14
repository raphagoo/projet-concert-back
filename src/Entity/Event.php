<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageThumbnail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imagePoster;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $artistName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $parking;

    /**
     * @ORM\Column(type="boolean")
     */
    private $restaurant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $songLink;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $artistDescription;

    /**
     * @ORM\ManyToOne(targetEntity=Salle::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $salle;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="event")
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    public function getImageThumbnail(): ?string
    {
        return $this->imageThumbnail;
    }

    public function setImageThumbnail(?string $imageThumbnail): self
    {
        $this->imageThumbnail = $imageThumbnail;

        return $this;
    }

    public function getImagePoster(): ?string
    {
        return $this->imagePoster;
    }

    public function setImagePoster(?string $imagePoster): self
    {
        $this->imagePoster = $imagePoster;

        return $this;
    }

    public function getArtistName(): ?string
    {
        return $this->artistName;
    }

    public function setArtistName(string $artistName): self
    {
        $this->artistName = $artistName;

        return $this;
    }

    public function getParking(): ?bool
    {
        return $this->parking;
    }

    public function setParking(bool $parking): self
    {
        $this->parking = $parking;

        return $this;
    }

    public function getRestaurant(): ?bool
    {
        return $this->restaurant;
    }

    public function setRestaurant(bool $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function getSongLink(): ?string
    {
        return $this->songLink;
    }

    public function setSongLink(?string $songLink): self
    {
        $this->songLink = $songLink;

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
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addEvent($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeEvent($this);
        }

        return $this;
    }
}
