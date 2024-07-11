<?php

namespace App\Entity;

use App\Repository\SalonAccessRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalonAccessRepository::class)]
class SalonAccess
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'salonAccess')]
    private ?Salons $salon = null;

    #[ORM\ManyToOne(inversedBy: 'salonAccess')]
    private ?User $user = null;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
