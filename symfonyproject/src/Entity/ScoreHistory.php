<?php

namespace App\Entity;

use App\Repository\ScoreHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Guid\Guid;

#[ORM\Entity(repositoryClass: ScoreHistoryRepository::class)]
class ScoreHistory
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID)]
    private ?string $idHistory = null;

    #[ORM\Column]
    private ?int $points = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function __construct()
    {
        $this->idHistory = Guid::uuid4()->toString();
    }

    public function getIdHistory(): ?string
    {
        return $this->idHistory;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

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
}
