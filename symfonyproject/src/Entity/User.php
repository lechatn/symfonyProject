<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $profilePicture = null;

    #[ORM\OneToMany(targetEntity: HabitTracking::class, mappedBy: 'idUser')]
    private Collection $habitTrackings;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Group $idGroup = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column]
    private ?int $score = null;

    #[ORM\OneToMany(targetEntity: Mail::class, mappedBy: 'UserMail')]
    private Collection $mails;

    #[ORM\OneToMany(targetEntity: Mail::class, mappedBy: 'idSender')]
    private Collection $mails_send;

    public function __construct()
    {
        $this->habitTrackings = new ArrayCollection();
        $this->mails = new ArrayCollection();
        $this->mails_send = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

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
            $mail->setUserMail($this);
        }

        return $this;
    }

    public function removeMail(Mail $mail): static
    {
        if ($this->mails->removeElement($mail)) {
            // set the owning side to null (unless already changed)
            if ($mail->getUserMail() === $this) {
                $mail->setUserMail(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Mail>
     */
    public function getMailsSend(): Collection
    {
        return $this->mails_send;
    }

    public function addMailsSend(Mail $mailsSend): static
    {
        if (!$this->mails_send->contains($mailsSend)) {
            $this->mails_send->add($mailsSend);
            $mailsSend->setIdSender($this);
        }

        return $this;
    }

    public function removeMailsSend(Mail $mailsSend): static
    {
        if ($this->mails_send->removeElement($mailsSend)) {
            // set the owning side to null (unless already changed)
            if ($mailsSend->getIdSender() === $this) {
                $mailsSend->setIdSender(null);
            }
        }

        return $this;
    }
}
