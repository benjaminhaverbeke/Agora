<?php

namespace App\Entity;

use App\Repository\SalonsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;
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

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;


    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Assert\GreaterThan(value : 'today', message : "Date de campagne trop courte")]
    private ?\DateTimeImmutable $date_campagne;

    #[ORM\Column]
    #[Assert\GreaterThan(value : 'today', message: "Date de vote trop courte")]
    #[Assert\GreaterThan(propertyPath: 'date_campagne', message : "La date de clôture des votes doit être supérieure à la date de fin de campagne")]
    private ?\DateTimeImmutable $date_vote;

    /**
     * @var Collection<int, user>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'salons')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, user>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);

        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

        return $this;
    }

}
