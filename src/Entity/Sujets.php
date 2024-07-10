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

    #[ORM\Column]
    private ?int $salon_id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $campagne_date = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $vote_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalonId(): ?int
    {
        return $this->salon_id;
    }

    public function setSalonId(int $salon_id): static
    {
        $this->salon_id = $salon_id;

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
}
