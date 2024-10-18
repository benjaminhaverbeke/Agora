<?php

namespace App\Entity;

use App\Repository\ProposalRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProposalRepository::class)]
class Proposal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Salon::class)]
    private ?Salon $salon;

    #[ORM\ManyToOne(targetEntity: Sujet::class, cascade: ['persist'], inversedBy: 'proposals' )]
    private ?Sujet $sujet;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 1000)]
    #[Assert\Length(max: 1000, maxMessage: "La description ne peut pas comporter plus de 1000 signes")]
    private ?string $description = null;

    #[ORM\ManyToOne]
    private ?User $user;

    /**
     * @var Collection<int, Vote>
     */
    #[ORM\OneToMany(targetEntity: Vote::class, mappedBy: 'proposal', cascade: ['persist', 'remove'], orphanRemoval: true)]
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

    public function getSalon(): ?Salon
    {
        return $this->salon;
    }

    public function setSalon(Salon $salon): static
    {
        $this->salon = $salon;

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
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }


    public function addVote(Vote $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setProposal($this);

        }

        return $this;
    }

    public function removeVote(Vote $vote): static
    {
        $this->votes->removeElement($vote);

        return $this;
    }


}
