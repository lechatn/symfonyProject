<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $score = null;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'idGroup')]
    private Collection $users;

    #[ORM\OneToMany(targetEntity: ScoreHistory::class, mappedBy: 'idGroup')]
    private Collection $scoreHistories;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creatorId = null;

    #[ORM\OneToMany(targetEntity: Mail::class, mappedBy: 'idGroup')]
    private Collection $mails;

    #[ORM\OneToMany(targetEntity: HabitTracking::class, mappedBy: 'idGroup')]
    private Collection $habitTrackings;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->scoreHistories = new ArrayCollection();
        $this->mails = new ArrayCollection();
        $this->habitTrackings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setIdGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getIdGroup() === $this) {
                $user->setIdGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ScoreHistory>
     */
    public function getScoreHistories(): Collection
    {
        return $this->scoreHistories;
    }

    public function addScoreHistory(ScoreHistory $scoreHistory): static
    {
        if (!$this->scoreHistories->contains($scoreHistory)) {
            $this->scoreHistories->add($scoreHistory);
            $scoreHistory->setIdGroup($this);
        }

        return $this;
    }

    public function removeScoreHistory(ScoreHistory $scoreHistory): static
    {
        if ($this->scoreHistories->removeElement($scoreHistory)) {
            // set the owning side to null (unless already changed)
            if ($scoreHistory->getIdGroup() === $this) {
                $scoreHistory->setIdGroup(null);
            }
        }

        return $this;
    }

    public function getCreatorId(): ?User
    {
        return $this->creatorId;
    }

    public function setCreatorId(User $creatorId): static
    {
        $this->creatorId = $creatorId;

        return $this;
    }

    /**
     * @return Collection<int, Mail>
     */
    public function getMails(): Collection
    {
        return $this->mails;
    }

    public function addMail(Mail $mail): static
    {
        if (!$this->mails->contains($mail)) {
            $this->mails->add($mail);
            $mail->setIdGroup($this);
        }

        return $this;
    }

    public function removeMail(Mail $mail): static
    {
        if ($this->mails->removeElement($mail)) {
            // set the owning side to null (unless already changed)
            if ($mail->getIdGroup() === $this) {
                $mail->setIdGroup(null);
            }
        }

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
            $habitTracking->setIdGroup($this);
        }

        return $this;
    }

    public function removeHabitTracking(HabitTracking $habitTracking): static
    {
        if ($this->habitTrackings->removeElement($habitTracking)) {
            // set the owning side to null (unless already changed)
            if ($habitTracking->getIdGroup() === $this) {
                $habitTracking->setIdGroup(null);
            }
        }

        return $this;
    }
}
