<?php

namespace App\Entity;

use App\Repository\PresenceRepository;
use Doctrine\ORM\Mapping as ORM;

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

    public function getHeureIn(): ?DateTimeInterface
    {
        return $this->HeureIn;
    }

    public function setHeureIn(DateTimeInterface $HeureIn): self
    {
        $this->HeureIn = $HeureIn;

        return $this;
    }

    public function getHeureOut(): ?\DateTimeInterface
    {
        return $this->HeureOut;
    }

    public function setHeureOut(\DateTimeInterface $HeureOut): self
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
