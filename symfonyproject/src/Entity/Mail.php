<?php

namespace App\Entity;

use App\Repository\MailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MailRepository::class)]
class Mail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'mails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $UserMail = null;

    #[ORM\ManyToOne(inversedBy: 'mails')]
    private ?Group $idGroup = null;

    #[ORM\ManyToOne(inversedBy: 'mails_send')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idSender = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
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

    public function getUserMail(): ?User
    {
        return $this->UserMail;
    }

    public function setUserMail(?User $UserMail): static
    {
        $this->UserMail = $UserMail;

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

    public function getIdSender(): ?User
    {
        return $this->idSender;
    }

    public function setIdSender(?User $idSender): static
    {
        $this->idSender = $idSender;

        return $this;
    }
}
