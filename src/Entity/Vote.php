<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Proposal::class, cascade: ['persist'], inversedBy: 'votes')]
    private ?Proposal $proposal = null;

    #[ORM\ManyToOne(targetEntity: Sujet::class)]
    private ?Sujet $sujet = null;

    #[ORM\Column(length: 255)]
    private ?string $notes = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    private ?User $User = null;
    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProposal(): ?Proposal
    {
        return $this->proposal;
    }

    public function setProposal(Proposal $proposal): static
    {
        $this->proposal = $proposal;

        return $this;
    }

    public function getSujet(): ?Sujet
    {
        return $this->sujet;
    }

    public function setSujet(Sujet $sujet): static
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

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }


}
