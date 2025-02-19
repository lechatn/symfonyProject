<?php

namespace App\Entity;

use App\Repository\HabitTrackingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitTrackingRepository::class)]
class HabitTracking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'habitTrackings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Habits $idHabit = null;

    #[ORM\ManyToOne(inversedBy: 'habitTrackings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idUser = null;

    #[ORM\ManyToOne(inversedBy: 'habitTrackings')]
    private ?Group $idGroup = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getIdHabit(): ?Habits
    {
        return $this->idHabit;
    }

    public function setIdHabit(?Habits $idHabit): static
    {
        $this->idHabit = $idHabit;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdGroup(): ?Group
    {
        return $this->idGroup;
    }

    public function setIdGroup(?Group $idGroup): static
    {
        $this->idGroup = $idGroup;

        return $this;
    }
}
