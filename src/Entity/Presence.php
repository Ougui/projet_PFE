<?php

namespace App\Entity;

use App\Repository\PresenceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass=PresenceRepository::class)
 */
class Presence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $HeureIn;

    /**
     * @ORM\Column(type="time")
     */
    private $HeureOut;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="presence")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeureIn(): ?\DateTime
    {
        return $this->HeureIn;
    }

    public function setHeureIn(\DateTime $HeureIn): self
    {
        $this->HeureIn = $HeureIn;

        return $this;
    }

    public function getHeureOut(): ?\DateTime
    {
        return $this->HeureOut;
    }

    public function setHeureOut(\DateTime $HeureOut): self
    {
        $this->HeureOut = $HeureOut;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

        return $this;
    }
}
