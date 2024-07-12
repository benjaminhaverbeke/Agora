<?php

namespace App\Entity;

use App\Repository\SujetsRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SujetsRepository::class)]
class Sujets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: "sujets")]
    private ?Salons $salon = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $campagne_date = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $vote_date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalon(): ?Salons
    {
        return $this->salon;
    }

    public function setSalon(Salons $salon): static
    {
        $this->salon = $salon;

        return $this;
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

    public function getCampagneDate(): ?\DateTimeImmutable
    {
        return $this->campagne_date;
    }

    public function setCampagneDate(\DateTimeImmutable $campagne_date): static
    {
        $this->campagne_date = $campagne_date;

        return $this;
    }

    public function getVoteDate(): ?\DateTimeImmutable
    {
        return $this->vote_date;
    }

    public function setVoteDate(\DateTimeImmutable $vote_date): static
    {
        $this->vote_date = $vote_date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
