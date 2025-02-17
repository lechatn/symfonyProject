<?php

namespace App\Entity;

use App\Repository\HabitsTrackingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Guid\Guid;

#[ORM\Entity(repositoryClass: HabitsTrackingRepository::class)]
class HabitsTracking
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID)]
    private ?string $idTracking = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function __construct()
    {
        $this->idTracking = Guid::uuid4()->toString();
    }

    public function getIdTracking(): ?string
    {
        return $this->idTracking;
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
}
