<?php

namespace App\Entity;

use App\Repository\SalonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SalonRepository::class)]
class Salon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255, maxMessage: "Le titre ne peut pas comporter plus de 255 signes")]
    private ?string $title = null;

    #[ORM\Column(length: 1000)]
    #[Assert\Length(max: 1000, maxMessage: "La description ne peut pas comporter plus de 1000 signes")]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'])]
    private ?User $user = null;


    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Assert\GreaterThan(value: 'today', message: "Date de campagne trop courte")]
    private ?\DateTimeImmutable $date_campagne;

    #[ORM\Column]
    #[Assert\GreaterThan(value: 'today', message: "Date de vote trop courte")]
    #[Assert\GreaterThan(propertyPath: 'date_campagne',
        message: "La date de clôture des votes doit être supérieure à la date de fin de campagne")]
    private ?\DateTimeImmutable $date_vote;

    /**
     * @var Collection<int, user>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'salons', cascade: ['persist'])]
    private Collection $users;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'salon', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $messages;

    /**
     * @var Collection<int, Sujet>
     */
    #[ORM\OneToMany(targetEntity: Sujet::class, mappedBy: 'salon', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $sujets;

    #[ORM\OneToMany(targetEntity: Invitation::class, mappedBy: 'salon', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $invitations;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->sujets = new ArrayCollection();
        $this->invitations = new ArrayCollection();
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

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);

        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {

        $this->messages->removeElement($message);

        return $this;
    }

    /**
     * @return Collection<int, Sujet>
     */
    public function getSujets(): Collection
    {
        return $this->sujets;
    }

    public function addSujet(Sujet $sujet): static
    {
        if (!$this->sujets->contains($sujet)) {
            $this->sujets->add($sujet);
            $sujet->setSalon($this);
        }

        return $this;
    }

    public function removeSujet(Sujet $sujet): static
    {
        $this->sujets->removeElement($sujet);


        return $this;
    }


    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): static
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
            $invitation->setSalon($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): static
    {
        $this->invitations->removeElement($invitation);


        return $this;
    }

}
