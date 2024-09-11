<?php

namespace App\Entity;

use App\Repository\SujetRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: SujetRepository::class)]
class Sujet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;


    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user;

    #[ORM\OneToMany(targetEntity: Proposal::class, mappedBy: 'sujet', cascade: ['persist', 'remove'])]
    private Collection $proposals;

    #[ORM\ManyToOne(inversedBy: 'sujets')]
    private ?Salon $salon = null;


    public function __construct()
    {
        $this->proposals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalon(): ?Salon
    {
        return $this->salon;
    }

    public function setSalon(Salon $salon): static
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

    public function addProposal(Proposal $proposal): static
    {
        if (!$this->proposals->contains($proposal)) {
            $this->proposals->add($proposal);
            $proposal->setSujet($this);

        }

        return $this;
    }

    public function removeProposal(Proposal $proposal): static
    {

        $this->proposals->removeElement($proposal);

        return $this;

    }


}
