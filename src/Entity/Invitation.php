<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: InvitationRepository::class)]
class Invitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[JoinColumn(onDelete: 'CASCADE')]
    private ?User $sender = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[JoinColumn(onDelete: 'CASCADE')]
    private ?User $receiver = null;

    #[ORM\ManyToOne(targetEntity: Salons::class)]
    #[JoinColumn(onDelete: 'CASCADE')]
    private ?Salons $salon = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct() {

        $this->createdAt = new \DateTimeImmutable();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getSalon(): ?Salons
    {
        return $this->salon;
    }

    public function setSalon(?Salons $salon): static
    {
        $this->salon = $salon;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->createdAt = $created_at;
        return $this;
    }




}
