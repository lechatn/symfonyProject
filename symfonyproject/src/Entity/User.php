<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $profilePicture = null;

    #[ORM\OneToMany(targetEntity: HabitTracking::class, mappedBy: 'idUser')]
    private Collection $habitTrackings;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Group $idGroup = null;

    public function __construct()
    {
        $this->habitTrackings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

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
            $habitTracking->setIdUser($this);
        }

        return $this;
    }

    public function removeHabitTracking(HabitTracking $habitTracking): static
    {
        if ($this->habitTrackings->removeElement($habitTracking)) {
            // set the owning side to null (unless already changed)
            if ($habitTracking->getIdUser() === $this) {
                $habitTracking->setIdUser(null);
            }
        }

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
