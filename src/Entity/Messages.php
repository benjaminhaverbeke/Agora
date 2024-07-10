<?php

namespace App\Entity;

use App\Repository\MessagesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessagesRepository::class)]
class Messages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Users $user = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Salons $salon = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeImmutable $created_at;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

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

    public function getCreatedAt(): ? \DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(): void
    {
        $this->created_at = new \DateTimeImmutable();




    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
