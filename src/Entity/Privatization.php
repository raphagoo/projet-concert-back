<?php

namespace App\Entity;

use App\Repository\PrivatizationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PrivatizationRepository::class)
 */
class Privatization
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("privatization_details")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("privatization_details")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("privatization_details")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("privatization_details")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("privatization_details")
     */
    private $mail;

    /**
     * @ORM\Column(type="date")
     * @Groups("privatization_details")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     * @Groups("privatization_details")
     */
    private $time;

    /**
     * @ORM\Column(type="integer")
     * @Groups("privatization_details")
     */
    private $numberPerson;

    /**
     * @ORM\Column(type="text")
     * @Groups("privatization_details")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups("privatization_details")
     */
    private $budget;

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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
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

    public function getNumberPerson(): ?int
    {
        return $this->numberPerson;
    }

    public function setNumberPerson(int $numberPerson): self
    {
        $this->numberPerson = $numberPerson;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(float $budget): self
    {
        $this->budget = $budget;

        return $this;
    }
}
