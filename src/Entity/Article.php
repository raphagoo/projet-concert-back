<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"article", "article_list", "search"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article", "article_list", "search"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article", "article_list", "search"})
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     * @Groups({"article", "article_list", "search"})
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"article", "article_list", "search"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=ArticleCategory::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"article", "article_list", "search"})
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="likedArticles")
     * @ORM\JoinTable(name="article_userliked")
     */
    private $userLiked;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sharedArticles")
     * @ORM\JoinTable(name="article_usershared")
     */
    private $userShared;

    public function __construct()
    {
        $this->userLiked = new ArrayCollection();
        $this->userShared = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCategory(): ?ArticleCategory
    {
        return $this->category;
    }

    public function setCategory(?ArticleCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserLiked(): Collection
    {
        return $this->userLiked;
    }

    public function addUserLiked(User $userLiked): self
    {
        if (!$this->userLiked->contains($userLiked)) {
            $this->userLiked[] = $userLiked;
        }

        return $this;
    }

    public function removeUserLiked(User $userLiked): self
    {
        $this->userLiked->removeElement($userLiked);

        return $this;
    }

    /**
     * @return int
     * @Groups("article_list")
     */
    public function getUserLikedCount(): int
    {
        return $this->userLiked->count();
    }

    /**
     * @return Collection|User[]
     */
    public function getUserShared(): Collection
    {
        return $this->userShared;
    }

    public function addUserShared(User $userShared): self
    {
        if (!$this->userShared->contains($userShared)) {
            $this->userShared[] = $userShared;
        }

        return $this;
    }

    public function removeUserShared(User $userShared): self
    {
        $this->userShared->removeElement($userShared);

        return $this;
    }

    /**
     * @return int
     * @Groups("article_list")
     */
    public function getUserSharedCount(): int
    {
        return $this->userShared->count();
    }
}
