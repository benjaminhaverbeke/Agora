<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvitationRepository::class)]
class Invitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $sender = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $receiver = null;

    #[ORM\ManyToOne(targetEntity: Salons::class)]
    private ?Salons $salon = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeImmutable $createdAt;

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): void
    {
        $this->sender = $sender;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setRecipient(?User $recipient): void
    {
        $this->receiver = $recipient;
    }

    public function getSalon(): ?Salons
    {
        return $this->salon;
    }

    public function setSalon(?Salons $salon): void
    {
        $this->salon = $salon;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }




}
