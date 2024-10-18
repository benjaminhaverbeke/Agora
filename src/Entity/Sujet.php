<?php

namespace App\Entity;

use App\Repository\SujetRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SujetRepository::class)]
class Sujet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 1000)]
    #[Assert\Length(max: 1000, maxMessage: "La description ne peut pas comporter plus de 1000 signes")]
    private ?string $description = null;


    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'])]
    private ?User $user;

    #[ORM\OneToMany(targetEntity: Proposal::class, mappedBy: 'sujet', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $proposals;

    #[ORM\ManyToOne(targetEntity: Salon::class, inversedBy: 'sujets')]
    private ?Salon $salon = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'voted', cascade: ['persist'])]
    private Collection $voters;



    public function __construct()
    {
        $this->proposals = new ArrayCollection();
        $this->voters = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getVoters(): Collection
    {
        return $this->voters;
    }

    public function addVoter(User $voter): static
    {
        if (!$this->voters->contains($voter)) {
            $this->voters->add($voter);
        }

        return $this;
    }

    public function removeVoter(User $voter): static
    {
        $this->voters->removeElement($voter);

        return $this;
    }

}
