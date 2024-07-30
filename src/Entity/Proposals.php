<?php

namespace App\Entity;

use App\Repository\ProposalsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ProposalsRepository::class)]
class Proposals
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Salons::class)]
    private ?Salons $salon = null;

    #[ORM\ManyToOne(targetEntity: Sujets::class, inversedBy: 'proposals')]
    private ?Sujets $sujet = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Votes::class, mappedBy: 'proposal', cascade:["remove"])]
    private Collection $votes;

    public function __construct(){

        $this->votes = new ArrayCollection();


    }

    public function setId(int $id) : self
    {
        $this->id = $id;

        return $this;

    }

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

    public function getSujet(): ?Sujets
    {
        return $this->sujet;
    }

    public function setSujet(Sujets $sujet): static
    {
        $this->sujet = $sujet;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    /**
     * @param Collection $votes
     */
    public function addVote(Votes $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);

        }

        return $this;
    }

    public function removeVote(Votes $vote): static
    {
        $this->votes->removeElement($vote);

        return $this;
    }
}
