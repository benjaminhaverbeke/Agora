<?php

namespace App\Entity;

use App\Repository\VotesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: VotesRepository::class)]
class Votes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Proposals::class, inversedBy: 'votes')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private ?Proposals $proposal = null;

    #[ORM\ManyToOne(targetEntity: Sujets::class)]
    #[JoinColumn(onDelete: 'CASCADE')]
    private ?Sujets $sujet = null;

    #[ORM\Column(length: 255)]
    private ?string $notes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProposal(): ?Proposals
    {
        return $this->proposal;
    }

    public function setProposal(Proposals $proposal): static
    {
        $this->proposal = $proposal;

        return $this;
    }

    public function getSujet(): ?Sujets
    {
        return $this->sujet;
    }

    public function setSujet(Sujets $sujet): static
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}
