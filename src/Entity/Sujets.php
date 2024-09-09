<?php

namespace App\Entity;

use App\Repository\SujetsRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: SujetsRepository::class)]
class Sujets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Salons::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salons $salon;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;


    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    #[ORM\OneToMany(targetEntity: Proposals::class, mappedBy: "sujet", orphanRemoval: true)]
    private Collection $proposals;


    public function __construct()
    {
        $this->proposals = new ArrayCollection();
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

    public function getProposals(): Collection
    {
        return $this->proposals;
    }

    public function addProposal(Proposals $proposal): static
    {
        if (!$this->proposals->contains($proposal)) {
            $this->proposals->add($proposal);

        }

        return $this;
    }

    public function removeProposal(Proposals $proposal): static
    {
        $this->proposals->removeElement($proposal);

        return $this;
    }

}
