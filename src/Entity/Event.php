<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"event_details", "concert_details", "salle_details", "event_search", "concert_list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event_details", "concert_details", "salle_details", "event_search", "concert_list"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"event_details", "event_search"})
     */
    private $imageThumbnail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("event_details")
     */
    private $imagePoster;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event_details", "event_search"})
     */
    private $artistName;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("event_details")
     */
    private $parking;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("event_details")
     */
    private $restaurant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("event_details")
     */
    private $songLink;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("event_details")
     */
    private $artistDescription;

    /**
     * @ORM\ManyToOne(targetEntity=Salle::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"event_details", "event_search"})
     */
    private $salle;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="event")
     * @Groups({"event_details", "event_search"})
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Concert::class, mappedBy="event")
     * @Groups({"event_details","event_search"})
     */
    private $concerts;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->concerts = new ArrayCollection();
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

    /**
     * @return Collection|Concert[]
     */
    public function getConcerts(): Collection
    {
        return $this->concerts;
    }

    public function addConcert(Concert $concert): self
    {
        if (!$this->concerts->contains($concert)) {
            $this->concerts[] = $concert;
            $concert->setEvent($this);
        }

        return $this;
    }

    public function removeConcert(Concert $concert): self
    {
        if ($this->concerts->removeElement($concert)) {
            // set the owning side to null (unless already changed)
            if ($concert->getEvent() === $this) {
                $concert->setEvent(null);
            }
        }

        return $this;
    }
}
