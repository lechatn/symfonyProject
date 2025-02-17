<?php

namespace App\Entity;

use App\Repository\HabitsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Guid\Guid;

#[ORM\Entity(repositoryClass: HabitsRepository::class)]
class Habits
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID)]
    private ?string $idHabit = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $difficulty = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?string $frequency = null;

    public function __construct()
    {
        $this->idHabit = Guid::uuid4()->toString();
    }

    public function getIdHabit(): ?string
    {
        return $this->idHabit;
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

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(string $frequency): static
    {
        $this->frequency = $frequency;

        return $this;
    }
}
