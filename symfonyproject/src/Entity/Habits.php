<?php

namespace App\Entity;

use App\Repository\HabitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitsRepository::class)]
class Habits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $difficulty = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?string $frequency = null;

    #[ORM\OneToMany(targetEntity: HabitTracking::class, mappedBy: 'idHabit')]
    private Collection $habitTrackings;

    public function __construct()
    {
        $this->habitTrackings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, HabitTracking>
     */
    public function getHabitTrackings(): Collection
    {
        return $this->habitTrackings;
    }

    public function addHabitTracking(HabitTracking $habitTracking): static
    {
        if (!$this->habitTrackings->contains($habitTracking)) {
            $this->habitTrackings->add($habitTracking);
            $habitTracking->setIdHabit($this);
        }

        return $this;
    }

    public function removeHabitTracking(HabitTracking $habitTracking): static
    {
        if ($this->habitTrackings->removeElement($habitTracking)) {
            // set the owning side to null (unless already changed)
            if ($habitTracking->getIdHabit() === $this) {
                $habitTracking->setIdHabit(null);
            }
        }

        return $this;
    }
}
