<?php

namespace App\Entity;

use App\Repository\SalonsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalonsRepository::class)]
class Salons
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: "salons")]
    private ?User $user = null;


    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_campagne = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_vote = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDateCampagne(): ?\DateTimeImmutable
    {
        return $this->date_campagne;
    }

    public function setDateCampagne(\DateTimeImmutable $date_campagne): static
    {
        $this->date_campagne = $date_campagne;

        return $this;
    }

    public function getDateVote(): ?\DateTimeImmutable
    {
        return $this->date_vote;
    }

    public function setDateVote(\DateTimeImmutable $date_vote): static
    {
        $this->date_vote = $date_vote;

        return $this;
    }

}
